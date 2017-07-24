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
