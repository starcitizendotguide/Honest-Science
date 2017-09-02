<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageUserController extends Controller
{

    public function __construct()
    {
        \View::share('deprecated_amount', count(\App\Task::deprecatedQueue(true)));
        \View::share('verify_amount', count(\App\Task::verifyQueue(true)));
    }

    /**
     * The view of all users.
     */
    public function users() {
        return view('manage.user.show');
    }

}
