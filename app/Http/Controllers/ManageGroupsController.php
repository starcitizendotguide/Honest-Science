<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageGroupsController extends Controller
{

    /**
     * The view of all groups.
     */
    public function groups() {
        return view('manage.groups.show');
    }

}
