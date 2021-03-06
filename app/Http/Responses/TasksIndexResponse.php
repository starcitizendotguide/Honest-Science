<?php
/**
 * Created by PhpStorm.
 * User: Yonas
 * Date: 09.09.2017
 * Time: 00:13
 */

namespace App\Http\Responses;

use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Support\Responsable;
use Laratrust;

class TasksIndexResponse implements Responsable
{

    private $page;
    private $size;
    private $sortByUpdated;

    public function __construct(int $page = -1, int $size = -1, bool $sortByUpdated = false)
    {
        $this->page = $page;
        $this->size = $size;
        $this->sortByUpdated = $sortByUpdated;
    }

    public function toResponse($request) {
        $tasks = DB::table('tasks')
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
                'tasks.updated_at as taskUpdatedAt',
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
                'task_children.progress as childProgress',
                'task_children.created_at as childCreatedAt',
                'task_children.updated_at as childUpdatedAt'
            )->get();

        $task_types_map = DB::table('task_types_map')->select('*')->get();
        $task_children_types_map = DB::table('task_children_types_map')->select('*')->get();

        $types = DB::table('task_types')->select('*')->get();
        $statuses = DB::table('task_statuses')->select('*')->get();
        $sources = DB::table('task_sources')->select('*')->get();

        $computed = [];

        $canReadTask = Laratrust::can('read-task');

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

                //--- Status
                $childStatus = $statuses->get($entry->childStatus);

                //--- Types
                $_types = $task_children_types_map->where('task_child_id', $entry->childId);
                $_types_array = [];
                foreach($_types as $_type) {
                    $_types_array[] = $types->where('id', $_type->task_type_id)->first();
                }

                $computed[$entry->taskId]['children'][] = [
                    'id'            => $entry->childId,
                    'name'          => $entry->childName,
                    'description'   => $entry->childDescription,
                    'progress'      => $entry->childProgress,
                    'status'        => $childStatus,
                    'types'         => $_types_array,
                    'sources'       => $sources->where('child_id', $entry->childId),
                    'created_at'    => $entry->childCreatedAt,
                    'updated_at'    => $entry->childUpdatedAt,
                    'updated_at_diff' => Carbon::parse($entry->childUpdatedAt)->diffForHumans(),
                ];
            }
            //--- Add new parent task
            else {

                //--- Add data
                $_children = [];
                $_sources  = [];

                //--- If it is not standalone it has children
                if((bool) $entry->taskStandalone === false) {

                    //--- Status
                    $childStatus = $statuses->get($entry->childStatus);

                    //--- Types
                    $_types = $task_children_types_map->where('task_child_id', $entry->childId);
                    $_types_array = [];
                    foreach($_types as $_type) {
                        $_types_array[] = $types->where('id', $_type->task_type_id)->first();
                    }

                    $_children[] = [
                        'id'            => $entry->childId,
                        'name'          => $entry->childName,
                        'description'   => $entry->childDescription,
                        'progress'      => $entry->childProgress,
                        'status'        => $childStatus,
                        'types'         => $_types_array,
                        'sources'       => $sources->where('child_id', $entry->childId),
                        'created_at'    => $entry->childCreatedAt,
                        'updated_at'    => $entry->childUpdatedAt,
                        'updated_at_diff' => Carbon::parse($entry->childUpdatedAt)->diffForHumans(),
                    ];
                }
                //--- If it is standalone it has sources
                else {
                    $_sources = $sources->where('parent_id', $entry->taskId);
                }

                //--- Types
                $_types = $task_types_map->where('task_id', $entry->taskId);
                $_types_array = [];
                foreach($_types as $_type) {
                    $_types_array[] = $types->where('id', $_type->task_type_id)->first();
                }

                $model = [
                    'id'            => $entry->taskId,
                    'name'          => $entry->taskName,
                    'description'   => $entry->taskDescription,
                    'standalone'    => (bool) $entry->taskStandalone,
                    'verified'      => (bool) $entry->taskVerified,
                    'status'        => $statuses->where('id', $entry->taskStatus)->first(),
                    'types'         => $_types_array,
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
                    'updated_at'    => $entry->taskUpdatedAt,
                    'updated_at_diff' => Carbon::parse($entry->taskUpdatedAt)->diffForHumans(),
                ];

                $computed[$entry->taskId] = $model;
            }

        }

        if(
            ($this->sortByUpdated === true) ||
            (!($this->size === -1) && !($this->page === -1))
        ) {
            $computed = collect($computed);

            if($this->sortByUpdated === true) {
                $computed = $computed->sortByDesc(function($entry, $key) {
                   return Carbon::parse($entry['updated_at'])->getTimestamp();
                });
            }

            if(!($this->size === -1) && !($this->page === -1)) {
                $computed = $computed->slice((($this->page - 1) * $this->size), $this->size);
            }

            $computed = $computed->toArray();
        }

        return array_values($computed);
    }

}