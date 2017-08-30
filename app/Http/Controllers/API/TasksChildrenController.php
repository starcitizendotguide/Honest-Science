<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksChildrenController extends Controller
{

    public function index() {
        $children = \App\TaskChild::all();

        $data = collect([]);

        foreach ($children as $child) {

            $tmp = [];
            $tmp['id']          = $child->id;
            $tmp['name']        = $child->name;
            $tmp['progress']    = $child->progress;
            $tmp['type']        = $child->type;
            $tmp['description'] = $child->description;
            $tmp['status']      = $child->status()->first();
            $tmp['parent']      = $child->parent();
            $tmp['created_at']  = $child->created_at;
            $tmp['updated_at']  = $child->updated_at;

            $data->push($tmp);

        }

        return $data;
    }

    public function show($id) {
        $child = \App\TaskChild::where('id', '=', $id)->first();

        $data = [];

        $data['id']             = $child->id;
        $data['name']           = $child->name;
        $data['progress']       = $child->progress;
        $data['type']           = $child->type()->first();
        $data['description']    = $child->description;
        $data['status']         = $child->status()->first();
        $data['parent']         = $child->parent()->first();
        $data['created_at']     = $child->created_at;
        $data['updated_at']     = $child->updated_at;

        return $data;
    }

    public function task($task_id) {

        $children = \App\TaskChild::where('task_id', $task_id);

        $children = $children->get();
        $data = collect([]);

        foreach ($children as $child) {

            $tmp = [];
            $tmp['id']          = $child->id;
            $tmp['name']        = $child->name;
            $tmp['progress']    = $child->progress;
            $tmp['type']        = $child->type()->first();
            $tmp['description'] = $child->description;
            $tmp['status']      = $child->status()->first();
            $tmp['created_at']  = $child->created_at;
            $tmp['updated_at']  = $child->updated_at;

            $data->push($tmp);

        }

        return $data;
    }

    public function sources($id, $standalone) {
        if(strtolower($standalone) == 'true') {
            return \App\Task::find($id)->sources()->get();
        } else {
            return \App\TaskChild::find($id)->sources()->get();
        }
    }

}
