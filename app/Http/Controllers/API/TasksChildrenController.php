<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksChildrenController extends Controller
{

    /**
     * A list of all TaskChilds which the user can see.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index() {

        $children = \App\TaskChild::all();

        $data = collect([]);

        foreach ($children as $child) {

            if($child->parent->visibility < 0 && !\Laratrust::can('read-child')) {
                continue;
            }

            $tmp = [];
            $tmp['id']          = $child->id;
            $tmp['name']        = $child->name;
            $tmp['progress']    = $child->progress;
            $tmp['type']        = $child->type;
            $tmp['description'] = $child->description;
            $tmp['status']      = $child->status()->first();
            $tmp['parent']      = $child->parent();
            $tmp['created_at']  = $child->created_at;
            $tmp['updated_at']  = $child->updated_at;

            $data->push($tmp);

        }

        return $data;
    }

    /**
     * Shows a specific child but only if the user has the permission
     * to see it.
     *
     * @param $id The id of the child.
     * @return array
     */
    public function show($id) {

        $data = [];

        $data['id']             = null;
        $data['name']           = null;
        $data['progress']       = 0;
        $data['type']           = null;
        $data['description']    = null;
        $data['status']         = null;
        $data['parent']         = null;
        $data['created_at']     = null;
        $data['updated_at']     = null;

        if(!\Laratrust::can('read-child')) {
            return [];
        }

        $child = \App\TaskChild::find('id', '=', $id);

        $data['id']             = $child->id;
        $data['name']           = $child->name;
        $data['progress']       = $child->progress;
        $data['type']           = $child->type()->first();
        $data['description']    = $child->description;
        $data['status']         = $child->status()->first();
        $data['parent']         = $child->parent()->first();
        $data['created_at']     = $child->created_at;
        $data['updated_at']     = $child->updated_at;

        return $data;
    }

    /**
     * Returns all children (TaskChild) related to a task.
     *
     * @param $task_id The task id.
     * @return \Illuminate\Support\Collection
     */
    public function task($task_id) {

        $children = \App\TaskChild::where('task_id', $task_id);

        $children = $children->get();
        $data = collect([]);

        foreach ($children as $child) {

            $tmp = [];
            $tmp['id']          = $child->id;
            $tmp['name']        = $child->name;
            $tmp['progress']    = $child->progress;
            $tmp['type']        = $child->type()->first();
            $tmp['description'] = $child->description;
            $tmp['status']      = $child->status()->first();
            $tmp['created_at']  = $child->created_at;
            $tmp['updated_at']  = $child->updated_at;

            $data->push($tmp);

        }

        return $data;
    }

    /**
     * A list of all sources related to a Task, if it is standalone,
     * and to a TaskChild, if it is not standalone.
     *
     * @param $id The id of the Task or TaskChild.
     * @param $standalone If it is a standalone task (true) or a TaskChild (false).
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function sources($id, $standalone) {
        if(strtolower($standalone) == 'true') {
            return \App\Task::find($id)->sources()->get();
        } else {
            return \App\TaskChild::find($id)->sources()->get();
        }
    }

}
