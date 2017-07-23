<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\TaskChild;

class TasksController extends Controller
{

    public function index()
    {

        //--- Build data
        $data = [];

        $tasks = Task::all();
        foreach ($tasks as $task) {

            $tmp = [
                'id'            => $task['id'],
                'name'          => $task['name'],
                'description'   => $task['description'],
                'status'        => 1, //@TODO The status is based on the completion of all sub tasks
                'progress'      => 0,
                'children'      => []
            ];

            //--- Append Children
            $children = $task->children();

            if(!($children->exists())) {
                continue;
            }

            $children = $children->get();

            $childrenArray = [];
            $progress = 0;

            foreach ($children as $child) {
                $childrenArray[] = [
                    'id'            => $child['id'],
                    'name'          => $child['name'],
                    'description'   => $child['description'],
                    'status'        => $child['status'],
                    'progress'      => $child['progress'],
                    'type'          => $child['type']
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

        $data['id']             = $task['id'];
        $data['name']           = $task['name'];
        $data['description']    = $task['description'];
        $data['status']         = $task['status'];

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
                'id'            => $child['id'],
                'name'          => $child['name'],
                'description'   => $child['description'],
                'status'        => $child['status'],
                'progress'      => $child['progress'],
                'type'          => $child['type']
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
