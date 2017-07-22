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
                
                'progress'      => 0,
                'collapsed'     => false,
                'children'      => []
            ];

            //--- Append Children
            $children = $task->children()->get();
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

}
