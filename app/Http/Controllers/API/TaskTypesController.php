<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TaskType;

class TaskTypesController extends Controller
{

    /**
     * A list of all available types.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index() {
        return TaskType::all();
    }

}
