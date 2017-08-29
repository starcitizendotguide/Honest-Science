<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageContentController extends Controller
{

    //-------------
    //--- Tasks ---
    //-------------

    /**
     * The view of all tasks.
     */
    public function tasks() {
        return view('manage.tasks.show');
    }

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
            $task = \App\Task::byId($id);

            if($task === null) {
                dd(404 . ' - ManageContentController');
            }

            return view('manage.tasks.edit', [
                'task' => $task->first()
            ]);
        } else if($request->isMethod('POST')) {

            //--- Validate
            $this->validateTask($request);

            $task = \App\Task::byId($request->input('id'))->first();

            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->save();

            return view('manage.tasks.edit', [
                'task' => $task->first()
            ]);
        }
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

        //--- Redirect
        return redirect()->route('manage.content.tasks');
    }

    //-------------
    //--- Children ---
    //-------------
    /**
     * The view of creating a new task.
     */
    public function childCreate(Request $request, $parent) {

        $parent = \App\Task::byId($parent)->first();

        return view('manage.tasks.create_child', [
            'parent'    => $parent,
            'statuses'  => \App\TaskStatus::all(),
            'types'     => \App\TaskType::all()
        ]);
    }

    /**
     * GET: The view of updating a task.
     * POST: Updating the edited task.
     */
    public function taskEdit(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $child = \App\TaskChild::find($id);

            if($child === null) {
                dd(404 . ' - ManageContentController');
            }

            return view('manage.child.edit', [
                'child' => $child->first()
            ]);
        } else if($request->isMethod('POST')) {

            //--- Validate
            $this->validateChild($request);

            $task = \App\TaskChild::byId($request->input('id'))->first();

            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->save();

            return view('manage.child.edit', [
                'child' => $task->first()
            ]);
        }
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

        //--- Redirect
        $task = \App\Task::byId($request->input('parent'));

        if($task === null) {
            dd(404 . ' - ManageContentController');
        }

        return view('manage.tasks.edit', [
            'task' => $task->first()
        ]);
    }


    /**
     * GET: A list of all available statuses.
     */
    public function statuses() {
        return view('manage.statuses');
    }

    /**
     * The method to validate requests bind to 'Task'.
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


}
