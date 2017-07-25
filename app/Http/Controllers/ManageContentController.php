<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageContentController extends Controller
{

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


}
