<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageController extends Controller
{

    public function __construct()
    {
        \View::share('deprecated_amount', count(\App\Task::deprecatedQueue(true)));
        \View::share('verify_amount', count(\App\Task::verifyQueue(true)));
    }

    public function dashboard(Request $request)
    {
        return view('manage.dashboard');
    }

}
