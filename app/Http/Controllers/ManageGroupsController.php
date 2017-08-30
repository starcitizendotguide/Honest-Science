<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageGroupsController extends Controller
{

    public function __construct()
    {
        \View::share('queue_amount', count(\App\Task::queue()));
    }

    /**
     * The view of all groups.
     */
    public function groups() {
        return view('manage.groups.show');
    }

}
