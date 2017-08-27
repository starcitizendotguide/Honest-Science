<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TaskStatus;

class TaskStatusesController extends Controller
{

    public function index() {
        $data = TaskStatus::all();

        $data->each(function($item, $key) {
            $item['countRelative'] = $item->countRelative();
        });

        return $data;
    }

}
