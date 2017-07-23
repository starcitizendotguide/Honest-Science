<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageContentController extends Controller
{

    public function tasks() {
        return view('manage.tasks');
    }

}
