<?php

namespace App\Http\Controllers;

use App\Http\Responses\TasksIndexResponse;
use Illuminate\Http\Request;

use App\Task;
use App\TaskChild;
use App\TaskStatus;

class TasksController extends Controller
{

    /**
     * A list of all tasks and their children the user can see.
     *
     * @return TasksIndexResponse
     */
    public function index()
    {

        return new TasksIndexResponse(-1, -1, false);

    }

    /**
     * A specific page out of all items.
     *
     * @param $page The page.
     * @param $size The amount of Tasks you want.
     * @return TasksIndexResponse
     */
    public function paginatedIndex($page, $size)
    {

        return new TasksIndexResponse($page, $size, true);

    }

    /**
     * Returns a specific task and its children,
     * if the user can see it.
     *
     * @param $id The id of the task.
     * @return array
     */
    public function show($id)
    {

        $data = [
            'id'            => null,
            'name'          => null,
            'standalone'    => false,
            'visibility'    => null,
            'description'   => null,
            'verified'      => false,
            'status'        => null,
            'type'          => null,
            'progress'      => 0,

            'progress'      => 0,
            'children'      => []
        ];

        $task = Task::find($id);

        if(
            $task === null ||
            !\Laratrust::can('read-task') &&
            (
                $task->visibility < 0 ||
                $task->verified === false
            )
        ) {
            return $data;
        }

        $data['id']             = $task->id;
        $data['name']           = $task->name;
        $data['description']    = $task->description;
        $data['standalone']     = $task->standalone;
        $data['visibility']     = $task->visibility()->first();
        $data['sources']        = $task->sources;
        $data['status']         = $task->status()->first();
        $data['type']           = $task->type();
        $data['progress']       = ($task->standalone === true ? $task->progress : 0);

        $data['created_at']     = $task->created_at;
        $data['updated_at']     = $task->updated_at;

        if($task->standalone !== true) {
            //--- Append Children
            $children = $task->children();

            if(!($children->exists())) {
                return $data;
            }

            $children = $children->get();

            $childrenArray = [];
            $progress = 0;

            foreach ($children as $child) {
                $childrenArray[] = [
                    'id'            => $child->id,
                    'name'          => $child->name,
                    'description'   => $child->description,
                    'status'        => $child->status()->first(),
                    'progress'      => $child->progress,
                    'type'          => $child->type()->first(),

                    'sources'       => $child->sources()->get(),


                    'created_at'    => $child->created_at,
                    'updated_at'    => $child->updated_at
                ];

                $progress += $child['progress'];
            }

            //@TODO Is the overall progress of the parent task just the average
            //of all sub tasks? There should be some kind of weight be assigned
            // to each task...
            $data['progress'] = ($progress / count($childrenArray));
            $data['children'] = $childrenArray;
        }

        return $data;
    }

    /**
     * A list of tasks which are deprecated.
     *
     * @return array
     */
    public function deprecatedQueue() {

        if(!\Laratrust::can('mark-as-updated-task')) {
            return [];
        }

        return \App\Task::deprecatedQueue();
    }

    /**
     * A list of tasks which require verification.
     *
     * @return array
     */
    public function verifyQueue() {

        if(!\Laratrust::can('mark-as-verified-task')) {
            return [];
        }

        return \App\Task::verifyQueue();
    }

}
