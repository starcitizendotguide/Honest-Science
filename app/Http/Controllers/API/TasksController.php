<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\TaskChild;
use App\TaskStatus;

class TasksController extends Controller
{

    public function index()
    {

        $tasks = \DB::table('tasks')
            ->leftJoin('task_types', 'tasks.type', '=', 'task_types.id')
            ->leftJoin('visibility', 'tasks.visibility', '=', 'visibility.id')
            ->leftJoin('task_children', 'tasks.id', '=', 'task_children.task_id')
            ->select(
                //--- Task
                'tasks.id as taskId',
                'tasks.name as taskName',
                'tasks.description as taskDescription',
                'tasks.created_at as taskCreatedAt',
                'tasks.updated_at as taskUpdatedAt',
                'tasks.standalone as taskStandalone',
                'tasks.verified as taskVerified',
                'tasks.status as taskStatus',
                'tasks.progress as taskProgress',
                'tasks.type as taskType',
                //--- Type
                'task_types.id as typeId',
                'task_types.name as typeName',
                'task_types.css_icon as typeCssIcon',
                'task_types.created_at as typeCreatedAt',
                'task_types.updated_at as typeUpdatedAt',
                //--- Visibility
                'visibility.id as visibilityId',
                'visibility.name as visibilityName',
                'visibility.created_at as visibilityCreatedAt',
                'visibility.updated_at as visibilityUpdatedAt',
                //--- Child
                'task_children.id as childId',
                'task_children.name as childName',
                'task_children.description as childDescription',
                'task_children.status as childStatus',
                'task_children.type as childType',
                'task_children.progress as childProgress',
                'task_children.created_at as childCreatedAt',
                'task_children.updated_at as childUpdatedAt'
            )->get();

        $types = \DB::table('task_types')->select('*')->get();
        $statuses = \DB::table('task_statuses')->select('*')->get();
        $sources = \DB::table('task_sources')->select('*')->get();

        $computed = [];

        $canReadTask = \Laratrust::can('read-task');

        foreach($tasks as $entry) {

            if(
                !$canReadTask &&
                (
                    $entry->visibilityId < 0 ||
                    $entry->taskVerified === false
                )
            ) {
                continue;
            }

            $found = array_key_exists($entry->taskId, $computed);
            //--- Add a child
            if($found) {
                $childStatus = $statuses->get($entry->childStatus);
                $computed[$entry->taskId]['children'][] = [
                    'id'            => $entry->childId,
                    'name'          => $entry->childName,
                    'description'   => $entry->childDescription,
                    'progress'      => $entry->childProgress,
                    'status'        => $childStatus,
                    'type'          => $types->where('id', $entry->childType)->first(),
                    'sources'       => $sources->where('parent_id', $entry->childId),
                    'created_at'    => $entry->childCreatedAt,
                    'updated_at'    => $entry->childUpdatedAt
                ];
            }
            //--- Add new parent task
            else {

                //--- Add data
                $_children = [];
                $_sources  = [];

                //--- If it is not standalone it has children
                if((bool) $entry->taskStandalone === false) {
                    $childStatus = $statuses->get($entry->childStatus);

                    $_children[] = [
                        'id'            => $entry->childId,
                        'name'          => $entry->childName,
                        'description'   => $entry->childDescription,
                        'progress'      => $entry->childProgress,
                        'status'        => $childStatus,
                        'type'          => $types->where('id', $entry->taskType)->first(),
                        'sources'       => $sources->where('parent_id', $entry->childId),
                        'created_at'    => $entry->childCreatedAt,
                        'updated_at'    => $entry->childUpdatedAt
                    ];
                }
                //--- If it is standalone it has sources
                else {
                    $_sources = $sources->where('parent_id', $entry->taskId);
                }

                $taskStatus = $types->where('id', $entry->childType);

                $model = [
                    'id'            => $entry->taskId,
                    'name'          => $entry->taskName,
                    'description'   => $entry->taskDescription,
                    'standalone'    => (bool) $entry->taskStandalone,
                    'verified'      => (bool) $entry->taskVerified,
                    'status'        => $statuses->where('id', $entry->taskStatus)->first(),
                    'type'          => $taskStatus->isEmpty() ? null : $taskStatus->first(),
                    'progress'      => $entry->taskProgress,
                    'visibility'    => [
                        'id'            => $entry->visibilityId,
                        'name'          => $entry->visibilityName,
                        'created_at'    => $entry->visibilityCreatedAt,
                        'updated_at'    => $entry->visibilityUpdatedAt
                    ],
                    'children'      => $_children,
                    'sources'       => $_sources,
                    'created_at'    => $entry->taskCreatedAt,
                    'updated_at'    => $entry->taskUpdatedAt
                ];

                $computed[$entry->taskId] = $model;
            }

        }

        return array_values($computed);
        /*

        //--- Build data
        $data = [];
        $user = \Auth::user();

        $tasks = Task::all();
        foreach ($tasks as $task) {



            $tmp = [
                'id'            => $task->id,
                'name'          => $task->name,
                'description'   => $task->description,
                'standalone'    => $task->standalone,
                'visibility'    => $task->visibility()->first(),
                'verified'      => $task->verified,
                'sources'       => $task->sources,
                'status'        => $task->status(),
                'type'          => $task->type(),
                'progress'      => ($task->standalone === true ? $task->progress : 0),
                'children'      => [],

                'created_at'    => $task->created_at,
                'updated_at'    => $task->updated_at
            ];

            if($task->standalone === false) {
                //--- Append Children
                $children = $task->children();

                if(!($children->exists())) {
                    //--- Add without children
                    $data[] = $tmp;
                    continue;
                }

                $children = $children->get();

                $childrenArray = [];
                $progress = 0;


                foreach ($children as $child) {

                    //--- Build
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
                $tmp['progress'] = ($progress / count($childrenArray));
                $tmp['children'] = $childrenArray;
            }

            $data[] = $tmp;
        }

        return $data;
        */

    }

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
        $data['status']         = $task->status();
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

    public function deprecatedQueue() {

        if(!\Laratrust::can('mark-as-updated-task')) {
            return [];
        }

        return \App\Task::deprecatedQueue();
    }

    public function verifyQueue() {

        if(!\Laratrust::can('mark-as-verified-task')) {
            return [];
        }

        return \App\Task::verifyQueue();
    }

}
