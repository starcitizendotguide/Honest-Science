<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TaskStatus;

class TaskStatusesController extends Controller
{

    /**
     * A list of all statuses and the relative amount of standalone
     * tasks and task children they are related to.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index() {
        $data = TaskStatus::all();

        $data->each(function($item, $key) {
            $item['countRelative'] = $item->countRelative();
        });

        return $data;
    }

}
