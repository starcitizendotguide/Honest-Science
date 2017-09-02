<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageGroupsController extends Controller
{

    public function __construct()
    {
        \View::share('deprecated_amount', count(\App\Task::deprecatedQueue(true)));
        \View::share('verify_amount', count(\App\Task::verifyQueue(true)));
    }

    /**
     * The view of all groups.
     */
    public function groups() {
        return view('manage.groups.show');
    }

}
