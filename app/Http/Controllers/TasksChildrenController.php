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
            $tmp['status']      = $child->status();
            $tmp['parent']      = $child->parent();
            $tmp['created_at']  = $child->created_at;
            $tmp['updated_at']  = $child->updated_at;

            $data->push($tmp);

        }

        return $data;
    }

}
