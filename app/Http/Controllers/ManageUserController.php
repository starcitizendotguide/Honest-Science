<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageUserController extends Controller
{

    /**
     * The view of all users.
     */
    public function users() {
        return view('manage.user.show');
    }

}
