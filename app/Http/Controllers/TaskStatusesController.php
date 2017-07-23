<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TaskStatus;

class TaskStatusesController extends Controller
{

    public function index() {
        return TaskStatus::all();
    }

}
