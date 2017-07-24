<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageContentController extends Controller
{

    public function tasks() {
        return view('manage.tasks.show');
    }

    public function tasksCreate() {
        return view('manage.tasks.create');
    }

    public function tasksEdit(Request $request, $id) {

        //--- Request Method
        if($request->isMethod('GET')) {
            $task = \App\Task::find($id);

            if(!($task->exists())) {
                dd(404);
            }

            return view('manage.tasks.edit', [
                'task' => $task->first()
            ]);
        } else if($request->isMethod('POST')) {

            //--- Validate
            $this->validate($request, [
                'name'          => 'required|min:5',
                'description'   => 'required|min:30',
            ]);

            $task = \App\Task::find($request->input('id'))->first();

            $task->name = $request->input('name');
            $task->description = $request->input('description');
            $task->save();

            return view('manage.tasks.edit', [
                'task' => $task->first()
            ]);
        }
    }

    public function taskStore(Request $request) {

        //--- Validate
        $this->validate($request, [
            'name'          => 'required|min:5',
            'description'   => 'required|min:30',
        ]);

        //--- Store
        $task = new \App\Task;
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->save();

        //--- Redirect
        return redirect()->route('manage.content.tasks');
    }

    public function statuses() {
        return view('manage.statuses');
    }

}
