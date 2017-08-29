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
            $task = \App\Task::findOrfail($id);
        } else if($request->isMethod('POST')) {

            //--- Validate
            $this->validateTask($request);

            $task = \App\Task::findOrFail($request->input('id'));

            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->save();

        }

        return view('manage.tasks.edit', [
            'task' => $task
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

        //--- Redirect
        return redirect()->route('manage.content.tasks');
    }

    /**
     * Delete: Delete a task.
     */
    public function tasksDelete(Request $request, $id) {

        \App\Task::findOrFail($id)->delete();
        \App\TaskChild::where('task_id', $id)->delete();

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

        return redirect()->route('manage.content.child.edit', ['id' => $task->id]);
    }

    /**
     * Delete: Delete a child.
     */
    public function childDelete(Request $request, $id) {
        $child = \App\TaskChild::findOrFail($id);
        $parent = $child->task_id;
        $child->delete();

        //--- Redirect
        return redirect()->route('manage.content.tasks.edit', ['id' => $parent]);
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
