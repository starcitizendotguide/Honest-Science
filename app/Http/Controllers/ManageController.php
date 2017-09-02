<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageController extends Controller
{

    public function __construct()
    {
    }

    public function dashboard(Request $request)
    {
        return view('manage.dashboard');
    }

}
