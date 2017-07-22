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
                'status'        => $task['status'],
                'progress'      => $task['progress'], //TODO Computed... just demo data here
                'collapsed'     => false, //@Note: Required for JS stuff
                'children'      => []
            ];

            //--- Append Children
            $children = $task->children()->get();
            $childrenArray = [];
            foreach ($children as $child) {
                $childrenArray[] = [
                    'id'            => $child['id'],
                    'name'          => $child['name'],
                    'description'   => $child['description'],
                    'status'        => $child['status'],
                    'progress'      => $child['progress'], //TODO Computed... just demo data here
                    'type'          => $child['type']
                ];
            }
            $tmp['children'] = $childrenArray;

            $data[] = $tmp;
        }

        return $data;
    }

}
