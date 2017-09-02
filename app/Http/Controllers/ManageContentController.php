<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageContentController extends Controller
{

    public function __construct() {
    }

    //------------
    //--- Task ---
    //------------

    /**
     * The view of all tasks.
     */
    public function tasks() {
        return view('manage.tasks.show');
    }

    /**
    * Delete: Delete a task.
    */
    public function tasksDelete(Request $request, $id) {

        if(\Laratrust::can('delete-task')) {
            $task = \App\Task::findOrFail($id);

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
     * The view of creating a new task.
     */
    public function tasksCreate() {
        return view('manage.tasks.create');
    }


    /**
     * GET: The view of updating a task.
     * POST: Updating the edited task.
     */
    public function tasksEdit(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $task = \App\Task::findOrFail($id);
        } else if($request->isMethod('POST')) {

            if(\Laratrust::can('update-task')) {
                //--- Validate
                $this->validateTask($request);

                $task = \App\Task::findOrFail($id);

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
     * POST: Creating a new task.
     */
    public function taskStore(Request $request) {

        if(\Laratrust::can('create-task')) {
            //--- Validate
            $this->validateTask($request);

            //--- Store
            $task = new \App\Task;
            $task->name = $request->input('name');
            $task->description = $request->input('description');

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
     * The view of creating a new task.
     */
    public function tasksCreateStandalone() {
        return view('manage.tasks.standalone.create', [
            'statuses'  => \App\TaskStatus::all(),
            'types'     => \App\TaskType::all()
        ]);
    }

    /**
     * GET: The view of updating a task.
     * POST: Updating the edited task.
     */
    public function tasksEditStandalone(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $task = \App\Task::findOrfail($id);
        } else if($request->isMethod('POST')) {

            if(\Laratrust::can('update-task')) {
                //--- Validate
                $this->validateStandaloneTask($request);
                $task = \App\Task::findOrFail($id);
                $task->name = $request->input('name');
                $task->description = $request->input('description');
                $task->status = $request->input('status');
                $task->visibility = $request->input('visibility');
                $task->type = $request->input('type');
                $task->progress = ($request->input('progress') / 100);
                $task->save();

                \Session::flash('flash', ('Update successfully executed!'));
            } else {
                \Session::flash('flash', ('You cannot edit this task.'));
            }
        }

        return view('manage.tasks.standalone.edit', [
            'task'      => $task,
            'statuses'  => \App\TaskStatus::all(),
            'types'     => \App\TaskType::all(),
            'visibilities'  => \App\Visibility::all(),
        ]);
    }

    /**
     * POST: Creating a new task.
     */
    public function taskStoreStandalone(Request $request) {

        //--- Validate
        $this->validateStandaloneTask($request);

        //--- Store
        if(\Laratrust::can('create-task')) {
            $task = new \App\Task;
            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->type = $request->input('type');
            $task->progress = ($request->input('progress') / 100);
            $task->standalone = true;
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
     * The view of creating a new task.
     */
    public function childCreate(Request $request, $parent) {

        $parent = \App\Task::find($parent);

        return view('manage.child.create', [
            'parent'    => $parent,
            'statuses'  => \App\TaskStatus::all(),
            'types'     => \App\TaskType::all()
        ]);
    }

    /**
     * GET: The view of updating a task.
     * POST: Updating the edited task.
     */
    public function childEdit(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $child = \App\TaskChild::findOrFail($id);
        } else if($request->isMethod('POST')) {

            if(\Laratrust::can('update-child')) {
                //--- Validate
                $this->validateChild($request);

                $child = \App\TaskChild::findOrFail($id);

                $child->name = $request->input('name');
                $child->description = $request->input('description');
                $child->status = $request->input('status');
                $child->type = $request->input('type');
                $child->progress = ($request->input('progress') / 100);
                $child->save();

                \Session::flash('flash', ('Update successfully executed!'));
            } else {
                \Session::flash('flash', ('You cannot edit this task!'));
            }

        }

        return view('manage.child.edit', [
            'child'     => $child,
            'parent'    => $child->parent()->first(),
            'statuses'  => \App\TaskStatus::all(),
            'types'     => \App\TaskType::all()
        ]);

    }

    /**
     * POST: Creating a new task.
     */
    public function childStore(Request $request) {

        if(\Laratrust::can('update-child')) {
            //--- Validate
            $this->validateChild($request);

            //--- Store
            $child = new \App\TaskChild;
            $child->task_id = $request->input('parent');
            $child->name = $request->input('name');
            $child->description = $request->input('description');
            $child->status = $request->input('status');
            $child->type = $request->input('type');
            $child->progress = ($request->input('progress') / 100);
            $child->save();

            \Session::flash('flash', ('You successfully added a child.'));
            return redirect()->route('manage.content.child.edit', ['id' => $child->id]);

        } else {
            \Session::flash('flash', ('You cannot create a new child.'));
            $parent = \App\Task::findOrFail($request->input('parent'));

            //--- Redirect
            return redirect()->route('manage.content.tasks.edit', ['id' => $parent->id]);
        }
    }

    /**
     * Delete: Delete a child.
     */
    public function childDelete(Request $request, $id) {

        $child = \App\TaskChild::findOrFail($id);

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
     * The view of creating a new task.
     */
    public function sourceCreate(Request $request, $id, $standalone) {

        $object = null;
        if($standalone == '1') {
            $object = \App\Task::findOrFail($id);
        } else {
            $object = \App\TaskChild::findOrFail($id);
        }

        //--- Redirect
        return view('manage.source.create', [
            'object'        => $object,
            'standalone'    => $standalone
        ]);
    }

    /**
     * POST: Creating a new task.
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
     * Delete: Delete a child.
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
     * GET: A list of all available statuses.
     */
    public function statuses() {
        return view('manage.statuses');
    }

    /**
     * The method to validate requests bind to 'Task Topic'.
     */
    public function validateTask($request) {
        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
        ]);
    }

    /**
     * The method to validate requests bind to 'TaskChild'.
     */
    public function validateChild($request) {
        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
            'type'          => 'required',
            'progress'      => 'required|numeric|between:0,100'
        ]);
    }


    /**
     * The method to validate requests bind to 'Task Standalone'.
     */
    public function validateStandaloneTask($request) {
        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
            'type'          => 'required',
            'progress'      => 'required|numeric|between:0,100'
        ]);
    }

    /**
     * The method to validate requests bind to 'TaskSource'.
     */
    public function validateSource($request) {
        //--- Validate
        $this->validate($request, [
            'link'          => 'required|url',
        ]);
    }

    //------------------------
    //--- Deprecated Queue ---
    //------------------------
    public function tasksDeprecatedQueue(Request $request) {
        return view('manage.tasks.deprecated');
    }

    public function taskDeprecatedChecked(Request $request, $id) {

        $task = \App\Task::findOrFail($id);
        $task->updated_at = \Carbon\Carbon::now();
        $task->save(['timestamps' => true]);

        \Session::flash('flash', ($task->name . ' (' . $task->id . ') is no longer deprecated.'));

        return redirect()->route('manage.content.tasks.deprecated');

    }

    //------------------------
    //--- Deprecated Queue ---
    //------------------------
    public function tasksVerifyQueue(Request $request) {
        return view('manage.tasks.verify');
    }

    public function taskVerifyChecked(Request $request, $id) {

        if(\Laratrust::can('mark-as-verified-task')) {
            $task = \App\Task::findOrFail($id);
            $task->verified = true;
            $task->save();

            \Session::flash('flash', ('Is ' . $task->name . ' (' . $task->id . ') now verified.'));
        } else {
            \Session::flash('flash', ('You cannot mark this task as verified.'));
        }

        return redirect()->route('manage.content.tasks.verify');

    }

    public function taskVerifyUncheck(Request $request, $id) {

        if(\Laratrust::can('mark-as-verified-task')) {
            $task = \App\Task::findOrFail($id);
            $task->verified = false;
            $task->save();

            \Session::flash('flash', ('Is ' . $task->name . ' (' . $task->id . ') no longer verified.'));
        } else {
            \Session::flash('flash', ('You cannot mark this task as unverified.'));
        }

        return redirect()->route('manage.content.tasks');

    }

}
