<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageContentController extends Controller
{

    public function __construct() {
        \View::share('queue_amount', count(\App\Task::queue()));
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
        //--- Redirect
        \Session::flash('flash', ('You successfully deleted \'' . $task->name . '\' and all related data!'));
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
            $task = \App\Task::findOrfail($id);
        } else if($request->isMethod('POST')) {

            //--- Validate
            $this->validateTask($request);

            $task = \App\Task::findOrFail($id);

            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->save();

            \Session::flash('flash', ('Update successfully executed!'));

        }

        return view('manage.tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * POST: Creating a new task.
     */
    public function taskStore(Request $request) {

        //--- Validate
        $this->validateTask($request);

        //--- Store
        $task = new \App\Task;
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->save();

        \Session::flash('flash', ('You successfully added a new subject task.'));

        //--- Redirect
        return view('manage.tasks.edit', [
            'task' => $task
        ]);
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

            //--- Validate
            $this->validateStandaloneTask($request);
            $task = \App\Task::findOrFail($id);
            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->status = $request->input('status');
            $task->type = $request->input('type');
            $task->progress = ($request->input('progress') / 100);
            $task->save();

            \Session::flash('flash', ('Update successfully executed!'));

        }

        return view('manage.tasks.standalone.edit', [
            'task'      => $task,
            'statuses'  => \App\TaskStatus::all(),
            'types'     => \App\TaskType::all()
        ]);
    }

    /**
     * POST: Creating a new task.
     */
    public function taskStoreStandalone(Request $request) {

        //--- Validate
        $this->validateStandaloneTask($request);

        //--- Store
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
    }


    //----------------
    //--- Children ---
    //----------------
    /**
     * The view of creating a new task.
     */
    public function childCreate(Request $request, $parent) {

        $parent = \App\Task::byId($parent)->first();


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

        //--- Validate
        $this->validateChild($request);

        //--- Store
        $task = new \App\TaskChild;
        $task->task_id = $request->input('parent');
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->status = $request->input('status');
        $task->type = $request->input('type');
        $task->progress = ($request->input('progress') / 100);
        $task->save();

        \Session::flash('flash', ('You successfully added a child.'));

        return redirect()->route('manage.content.child.edit', ['id' => $task->id]);
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
        $child->delete();

        //--- Redirect
        \Session::flash('flash', ('You successfully deleted \'' . $child->name . '\' and all related data!'));
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

        return view('manage.source.create', [
            'object'        => $object,
            'standalone'    => $standalone
        ]);
    }

    /**
     * POST: Creating a new task.
     */
    public function sourceStore(Request $request, $id, $standalone) {

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
    }

    /**
     * Delete: Delete a child.
     */
    public function sourceDelete(Request $request, $id) {
        $source = \App\TaskSource::findOrFail($id);

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

    //-------------
    //--- Queue ---
    //-------------
    public function tasksQueue(Request $request) {
        return view('manage.tasks.queue');
    }

    public function tasksChecked(Request $request, $id) {

        $task = \App\Task::findOrFail($id);
        $task->updated_at = \Carbon\Carbon::now();
        $task->save(['timestamps' => true]);

        \Session::flash('flash', ('Marked ' . $task->name . ' (' . $task->id . ') as up-to-date.'));

        return redirect()->route('manage.content.tasks.queue');

    }

}
