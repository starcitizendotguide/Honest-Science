<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\TaskChild;

class TasksController extends Controller
{

    public function show()
    {

        //--- Build data
        $data = [];

        $tasks = Task::all();
        foreach ($tasks as $task) {

            $data[$task['id']] = [
                'id'            => $task['id'],
                'name'          => $task['name'],
                'description'   => $task['description'],
                'status'        => $task['status'],
                'progress'      => $task['progress'], //TODO Computed... just demo data here
                'collapsed'     => false, //@Note: Required for JS stuff
                'children'      => []
            ];

            //--- Append Children
            $children = $task->children()->get();

            foreach ($children as $child) {
                $data[$task['id']]['children'][] = [
                    'id'            => $child['id'],
                    'name'          => $child['name'],
                    'description'   => $child['description'],
                    'status'        => $child['status'],
                    'progress'      => $child['progress'], //TODO Computed... just demo data here
                    'type'          => $child['type']
                ];
            }

        }

        return response()->json($data);
    }

}
