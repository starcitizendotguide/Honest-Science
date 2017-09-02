<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupsController extends Controller
{

    public function __construct() {
        $this->middleware('permission:read-user');
    }

    public function index()
    {
        $data = [];

        $all = \App\Role::all();


        foreach($all as $role) {

            $data[] = [
                'id'            => $role->id,
                'name'          => $role->name,
                'display_name'  => $role->display_name,
                'permissions'   => $role->permissions,
                'created_at'    => $role->created_at,
                'updated_at'    => $role->updated_at,
            ];

        }

        return $data;
    }

}
