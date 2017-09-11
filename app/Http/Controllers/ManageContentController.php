<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskChild;
use App\TaskStatus;
use App\TaskType;
use Illuminate\Http\Request;

class ManageContentController extends Controller
{

    public function __construct() {
    }

    //------------
    //--- Task ---
    //------------

    /**
     * The view showing a list of all tasks.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasks() {
        return view('manage.tasks.show');
    }

    /**
     * Deletes a task.
     *
     * @param Request $request
     * @param $id The id of the task.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tasksDelete(Request $request, $id) {

        if(\Laratrust::can('delete-task')) {
            $task = Task::findOrFail($id);

            if($task->standalone === true) {
                \App\TaskSource::where('parent_id', '=', $task->id)->delete();
            } else {
                foreach($task->children()->get() as $child) {
                    \App\TaskSource::where('child_id', '=', $child->id)->delete();
                    $child->delete();
                }
            }

            $task->delete();
            \Session::flash('flash', ('You successfully deleted \'' . $task->name . '\' and all related data!'));
        } else {
            \Session::flash('flash', ('You cannot delete this task.'));
        }

        //--- Redirect
        return redirect()->route('manage.content.tasks');
    }

    //--------------------
    //--- Subject Task ---
    //--------------------

    /**
     * The view showing a form to create a 'Subject Task'.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasksCreate() {
        return view('manage.tasks.create');
    }


    /**
     * This method supports two different HTTP methods:
     *  - GET: Returns the appropriate view to edit the task.
     *  - POST: Expects inputs which are used to update the task.
     *
     * @param Request $request
     * @param $id The id of the task.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasksEdit(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $task = Task::findOrFail($id);
        } else if($request->isMethod('POST')) {

            if(\Laratrust::can('update-task')) {
                //--- Validate
                $this->validateTask($request);

                $task = Task::findOrFail($id);

                $task->name = $request->input('name');
                $task->description = $request->input('description');
                $task->visibility = $request->input('visibility');

                $task->save();

                \Session::flash('flash', ('Update successfully executed!'));
            } else {
                \Session::flash('flash', ('You cannot edit this task.'));
            }
        }

        return view('manage.tasks.edit', [
            'task'          => $task,
            'visibilities'  => \App\Visibility::all(),
        ]);
    }

    /**
     * This method stores a new 'Subject Task' in the database and
     * marks it as verified if the user creating the task has
     * the required permission.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function taskStore(Request $request) {

        if(\Laratrust::can('create-task')) {
            //--- Validate
            $this->validateTask($request);

            //--- Store
            $task = new Task;
            $task->name = $request->input('name');
            $task->description = $request->input('description');

            if(\Laratrust::can('mark-as-verified-task')) {
                $task->verified = true;
            }

            $task->save();

            \Session::flash('flash', ('You successfully added a new subject task.'));
            //--- Redirect
            return redirect()->route('manage.content.tasks.edit', ['id' => $task->id]);
        } else {
            \Session::flash('flash', ('You cannot create a new task.'));
            //--- Redirect
            return redirect()->route('manage.content.tasks');
        }

    }

    //------------------------
    //--- Standalone Tasks ---
    //------------------------

    /**
     * Returns the view for creating 'Standalone Task'.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasksCreateStandalone() {
        return view('manage.tasks.standalone.create', [
            'statuses'  => TaskStatus::all(),
            'types'     => TaskType::all()
        ]);
    }

    /**
     * This method supports two different HTTP methods:
     *  - GET: Returns the appropriate view to edit the task.
     *  - POST: Expects inputs which are used to update the task.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasksEditStandalone(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $task = Task::findOrfail($id);
        } else if($request->isMethod('POST')) {

            if(\Laratrust::can('update-task')) {
                //--- Validate
                $this->validateStandaloneTaskEdit($request);
                $task = Task::findOrFail($id);
                $task->name = $request->input('name');
                $task->description = $request->input('description');
                $task->status = $request->input('status');
                $task->visibility = $request->input('visibility');
                $task->type = $request->input('type');

                $status = TaskStatus::find($request->input('status'));
                $progress = ($request->input('progress') / 100);
                if ($progress < $status->value_min) {
                    $task->progress = $status->value_min;
                } else if ($task->progress > $status->value_max) {
                    $task->progress = $status->value_max;
                } else {
                    $task->progress = $progress;
                }

                $task->save();

                \Session::flash('flash', ('Update successfully executed!'));
            } else {
                \Session::flash('flash', ('You cannot edit this task.'));
            }
        }

        return view('manage.tasks.standalone.edit', [
            'task'      => $task,
            'statuses'  => TaskStatus::all(),
            'types'     => TaskType::all(),
            'visibilities'  => \App\Visibility::all(),
        ]);
    }

    /**
     * This method stores a new 'Standalone Task' in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function taskStoreStandalone(Request $request) {

        //--- Validate
        $this->validateStandaloneTaskStore($request);

        //--- Store
        if(\Laratrust::can('create-task')) {

            $status = TaskStatus::find($request->input('status'));

            $task = new Task;
            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->type = $request->input('type');
            $task->progress = $status->value_min;
            $task->standalone = true;


            if(\Laratrust::can('mark-as-verified-task')) {
                $task->verified = true;
            }

            $task->save();

            \Session::flash('flash', ('You successfully added a new standalone task.'));

            //--- Redirect
            return redirect()->route('manage.content.tasks.edit.update_standalone', [
                'id' => $task->id
            ]);
        } else {
            \Session::flash('flash', ('You cannot create a new task.'));
            return redirect()->route('manage.content.tasks');
        }

    }

    //----------------
    //--- Children ---
    //----------------

    /**
     * This method returns the view for creating a new child task for a 'Subject Task'.
     *
     * @param Request $request
     * @param $parent The parent task of the child.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function childCreate(Request $request, $parent) {

        $parent = Task::find($parent);

        return view('manage.child.create', [
            'parent'    => $parent,
            'statuses'  => TaskStatus::all(),
            'types'     => TaskType::all()
        ]);
    }

    /**
     * This method supports two different HTTP methods:
     *  - GET: Returns the appropriate view to edit the child.
     *  - POST: Expects inputs which are used to update the child.
     *
     * @param Request $request
     * @param $id The id of the child.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function childEdit(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $child = TaskChild::findOrFail($id);
        } else if($request->isMethod('POST')) {

            if(\Laratrust::can('update-child')) {
                //--- Validate
                $this->validateChildEdit($request);

                $child = TaskChild::findOrFail($id);


                $child->name = $request->input('name');
                $child->description = $request->input('description');
                $child->status = $request->input('status');
                $child->type = $request->input('type');

                $progress = ($request->input('progress') / 100);
                $status = TaskStatus::find($request->input('status'));
                if ($progress < $status->value_min) {
                    $child->progress = $status->value_min;
                } else if ($progress > $status->value_max) {
                    $child->progress = $status->value_max;
                } else {
                    $child->progress = $progress;
                }


                $child->save();

                \Session::flash('flash', ('Update successfully executed!'));
            } else {
                \Session::flash('flash', ('You cannot edit this task!'));
            }

        }

        return view('manage.child.edit', [
            'child'     => $child,
            'parent'    => $child->parent()->first(),
            'statuses'  => TaskStatus::all(),
            'types'     => TaskType::all()
        ]);

    }

    /**
     * This method stores a new child in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function childStore(Request $request) {

        if(\Laratrust::can('update-child')) {
            //--- Validate
            $this->validateChildStore($request);

            $status = TaskStatus::find($request->input('status'));

            //--- Store
            $child = new TaskChild;
            $child->task_id = $request->input('parent');
            $child->name = $request->input('name');
            $child->description = $request->input('description');
            $child->status = $request->input('status');
            $child->type = $request->input('type');
            $child->progress = $status->value_min;
            $child->save();

            \Session::flash('flash', ('You successfully added a child.'));
            return redirect()->route('manage.content.child.edit', ['id' => $child->id]);

        } else {
            \Session::flash('flash', ('You cannot create a new child.'));
            $parent = Task::findOrFail($request->input('parent'));

            //--- Redirect
            return redirect()->route('manage.content.tasks.edit', ['id' => $parent->id]);
        }
    }

    /**
     * This method deletes a child from the database.
     *
     * @param Request $request
     * @param $id The id of the child.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function childDelete(Request $request, $id) {

        $child = TaskChild::findOrFail($id);

        //--- Delete Sources
        \App\TaskSource::where('child_id', '=', $id)->delete();

        //--- Delete
        $parent = $child->task_id;

        if(\Laratrust::can('delete-child')) {
            $child->delete();
            \Session::flash('flash', ('You successfully deleted \'' . $child->name . '\' and all related data!'));
        } else {
            \Session::flash('flash', ('You cannot delete this child.'));
        }

        //--- Redirect
        return redirect()->route('manage.content.tasks.edit', ['id' => $parent]);
    }

    //---------------
    //--- Sources ---
    //---------------

    /**
     * This method returns the view for creating a new database.
     *
     * @param Request $request
     * @param $id The id of the task, if it is standalone, otherwise the id of the child.
     * @param $standalone If it is a standalone task or a child to append the task to.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sourceCreate(Request $request, $id, $standalone) {

        $object = null;
        if($standalone == '1') {
            $object = Task::findOrFail($id);
        } else {
            $object = TaskChild::findOrFail($id);
        }

        //--- Redirect
        return view('manage.source.create', [
            'object'        => $object,
            'standalone'    => $standalone
        ]);
    }

    /**
     * This method stores a new source in the database.
     *
     * @param Request $request
     * @param $id The id of the task, if it is standalone, otherwise the id of the child.
     * @param $standalone If it is a standalone task or a child to append the task to.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sourceStore(Request $request, $id, $standalone) {

        if(\Laratrust::can('create-source')) {
            //--- Validate
            $this->validateSource($request);

            //--- Store
            $source = new \App\TaskSource;

            if($standalone == '1') {
                $source->parent_id = $id;
            } else {
                $source->child_id = $id;
            }

            $source->link = $request->input('link');
            $source->save();


            \Session::flash('flash', ('You successfully added a source.'));

            //--- Redirect
            if($standalone == '1') {
                return redirect()->route('manage.content.tasks.edit.update_standalone', [
                    'id' => $source->parent_id
                ]);
            } else {
                return redirect()->route('manage.content.child.edit', [
                    'id' => $source->child_id
                ]);
            }
        } else {
            \Session::flash('flash', ('You cannot create a new source.'));
            //--- Redirect
            if($standalone == '1') {
                return redirect()->route('manage.content.tasks.edit.update_standalone', ['id' => $id]);
            } else {
                return redirect('manage.content.child.edit')->route(['id' => $id]);
            }
        }
    }

    /**
     * This method deletes a source from the database.
     *
     * @param Request $request
     * @param $id The id of the source.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sourceDelete(Request $request, $id) {

        $source = \App\TaskSource::findOrFail($id);

        if(\Laratrust::can('delete-source')) {
            \Session::flash('flash', ('You successfully deleted \'' . $source->link . '\' and all related data!'));

            //--- Standalone Task
            if($source->child_id === null) {
                $task = $source->parent_id;
                $source->delete();

                //--- Redirect
                return redirect()->route('manage.content.tasks.edit_standalone', ['id' => $task]);
            }
            //--- Subject Task
            else {
                $child = $source->child_id;
                $source->delete();

                //--- Redirect
                return redirect()->route('manage.content.child.edit', ['id' => $child]);
            }
        } else {
            \Session::flash('flash', ('You cannot delete this source.'));
            if($source->child_id === null) {
                return redirect()->route('manage.content.tasks.edit_standalone', ['id' => $source->parent_id]);
            } else {
                return redirect()->route('manage.content.child.edit', ['id' => $source->child_id]);
            }
        }

    }

    /**
     * Returns a view displaying all available task statuses.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statuses() {
        return view('manage.statuses');
    }

    //------------------------
    //--- Deprecated Queue ---
    //------------------------

    /**
     * Returns the view displaying the deprecated queue.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasksDeprecatedQueue(Request $request) {
        return view('manage.tasks.deprecated');
    }

    /**
     * Marks a task as up-to-date.
     *
     * @param Request $request
     * @param $id The id of the task.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function taskDeprecatedChecked(Request $request, $id)
    {

        if (\Laratrust::can('mark-as-updated-task')) {
            $task = Task::findOrFail($id);
            $task->updated_at = \Carbon\Carbon::now();
            $task->save(['timestamps' => true]);

            \Session::flash('flash', ($task->name . ' (' . $task->id . ') is no longer deprecated.'));
        } else {
            \Session::flash('flash', ('You cannot mark this task as up-to-date.'));
        }

        return redirect()->route('manage.content.tasks.deprecated');

    }

    //--------------------
    //--- Verify Queue ---
    //--------------------

    /**
     * Returns the view displaying all tasks waiting to verified.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tasksVerifyQueue(Request $request) {
        return view('manage.tasks.verify');
    }

    /**
     * Marks a task as verified.
     *
     * @param Request $request
     * @param $id The id of the task.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function taskVerifyChecked(Request $request, $id) {

        if(\Laratrust::can('mark-as-verified-task')) {
            $task = Task::findOrFail($id);
            $task->verified = true;
            $task->save();

            \Session::flash('flash', ('Is ' . $task->name . ' (' . $task->id . ') now verified.'));
        } else {
            \Session::flash('flash', ('You cannot mark this task as verified.'));
        }

        return redirect()->route('manage.content.tasks.verify');

    }

    /**
     * Marks a task as unverified.
     *
     * @param Request $request
     * @param $id The id of the task.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function taskVerifyUncheck(Request $request, $id) {

        if(\Laratrust::can('mark-as-verified-task')) {
            $task = Task::findOrFail($id);
            $task->verified = false;
            $task->save();

            \Session::flash('flash', ('Is ' . $task->name . ' (' . $task->id . ') no longer verified.'));
        } else {
            \Session::flash('flash', ('You cannot mark this task as unverified.'));
        }

        return redirect()->route('manage.content.tasks');

    }

    //------------------
    //--- Validators ---
    //------------------

    /**
     * This method validates the input for 'Subject Task'.
     *
     * @param $request
     */
    public function validateTask($request) {
        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
        ]);
    }

    /**
     * This method validates the input for a child.
     *
     * @param $request
     */
    public function validateChildEdit($request) {

        $status = null;

        if($request->has('status')) {
            $status = TaskStatus::find($request->get('status'));
        }

        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
            'type'          => 'required',
            'progress'      => ('required|numeric|between:' . ($status === null ? '0,100' : ($status->value_min * 100 . ',' . $status->value_max * 100)) . ''),
            'status'        => 'required',
        ]);
    }

    /**
     * This method validates the input for a child.
     *
     * @param $request
     */
    public function validateChildStore($request) {
        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
            'type'          => 'required',
        ]);
    }

    /**
     * This method validates the input for a 'Standalone Task' store action.
     *
     * @param $request
     */
    public function validateStandaloneTaskStore($request) {
        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
            'type'          => 'required',
        ]);
    }

    /**
     * This method validates the input for a 'Standalone Task'.
     *
     * @param $request
     */
    public function validateStandaloneTaskEdit($request) {

        $status = null;

        if($request->has('status')) {
            $status = TaskStatus::find($request->get('status'));
        }

        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
            'type'          => 'required',
            'status'        => 'required',
            'progress'      => ('required|numeric|between:' . ($status === null ? '0,100' : ($status->value_min * 100 . ',' . $status->value_max * 100)) . ''),
        ]);
    }

    /**
     * This method validates the input for a source.
     *
     * @param $request
     */
    public function validateSource($request) {
        //--- Validate
        $this->validate($request, [
            'link'          => 'required|url',
        ]);
    }


}
