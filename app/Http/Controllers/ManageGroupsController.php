<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageGroupsController extends Controller
{

    /**
     * Returns a view displaying all permission groups.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groups() {
        return view('manage.groups.show');
    }

}
