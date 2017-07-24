<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\TaskChild;
use App\TaskStatus;

class TasksController extends Controller
{

    public function index()
    {

        //--- Build data
        $data = [];

        $tasks = Task::all();
        foreach ($tasks as $task) {

            $tmp = [
                'id'            => $task->id,
                'name'          => $task->name,
                'description'   => $task->description,
                'status'        => $task->status(),
                'progress'      => 0,
                'children'      => [],

                'created_at'    => $task->created_at,
                'updated_at'    => $task->updated_at
            ];

            //--- Append Children
            $children = $task->children();

            if(!($children->exists())) {
                //--- Add without children
                $data[] = $tmp;
                continue;
            }

            $children = $children->get();

            $childrenArray = [];
            $progress = 0;

            foreach ($children as $child) {
                $childrenArray[] = [
                    'id'            => $child->id,
                    'name'          => $child->name,
                    'description'   => $child->description,
                    'status'        => $child->status(),
                    'progress'      => $child->progress,
                    'type'          => $child->type,

                    'created_at'    => $child->created_at,
                    'updated_at'    => $child->updated_at
                ];

                $progress += $child['progress'];
            }

            //@TODO Is the overall progress of the parent task just the average
            //of all sub tasks? There should be some kind of weight be assigned
            // to each task...
            $tmp['progress'] = ($progress / count($childrenArray));
            $tmp['children'] = $childrenArray;

            $data[] = $tmp;
        }

        return $data;
    }

    public function show($id)
    {

        $data = [
            'id'            => null,
            'name'          => null,
            'description'   => null,
            'status'        => null,

            'progress'      => 0,
            'children'      => []
        ];

        $task = Task::where('id', '=', $id);

        if(!($task->exists()))
        {
            return $data;
        }

        $task = $task->first();

        $data['id']             = $task->id;
        $data['name']           = $task->name;
        $data['description']    = $task->description;
        $data['status']         = $task->status();
        $data['created_at']     = $task->created_at;
        $data['updated_at']     = $task->updated_at;

        //--- Append Children
        $children = $task->children();

        if(!($children->exists())) {
            return $data;
        }

        $children = $children->get();

        $childrenArray = [];
        $progress = 0;

        foreach ($children as $child) {
            $childrenArray[] = [
                'id'            => $child->id,
                'name'          => $child->name,
                'description'   => $child->description,
                'status'        => $child->status(),
                'progress'      => $child->progress,
                'type'          => $child->type,

                'created_at'    => $child->created_at,
                'updated_at'    => $child->updated_at
            ];

            $progress += $child['progress'];
        }

        //@TODO Is the overall progress of the parent task just the average
        //of all sub tasks? There should be some kind of weight be assigned
        // to each task...
        $data['progress'] = ($progress / count($childrenArray));
        $data['children'] = $childrenArray;

        return $data;
    }

}
