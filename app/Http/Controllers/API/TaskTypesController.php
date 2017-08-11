<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TaskType;

class TaskTypesController extends Controller
{

    public function index() {
        return TaskType::all();
    }

}
