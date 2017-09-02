<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\TaskStatus;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
        'visibility',
        'status',
        'type',
        'progress',
        'standalone'
    ];

    protected $casts = [
        'standalone'    => 'boolean',
        'verified'      => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function visibility() {
        return $this->hasOne('App\Visibility', 'id', 'visibility');
    }

    public function children() {
        return $this->hasMany('App\TaskChild');
    }

    public function type() {
        return $this->hasOne('App\TaskType', 'id', 'type')->first();
    }

    public function sources() {
        return $this->hasMany('App\TaskSource', 'parent_id', 'id');
    }

    public function status() {

        //--- Standalone Status
        if($this->standalone === true) {
            return TaskStatus::find($this->status);
        }

        $DEFAULT_STATUS = 3;

        //--- No children is or should be invalid, so we return the default status
        $children = null;

        if(!($children = $this->children())->exists()) {
            return TaskStatus::find($DEFAULT_STATUS);
        }

        //--- Score
        $children = $children->get();
        $table = [];

        $children->each(function($item, $key) use (&$table) {
            $status = $item->status()->first();

            if($status['id'] === -1) {
                return;
            }

            if(array_key_exists($status['id'], $table)) {
                $table[$status['id']] += (1 * $status['rating']);
            } else {
                $table[$status['id']] = (1 * $status['rating']);
            }
        });
        $table = collect($table);
        $table = $table->sort();

        // @Note The idea behind this is that the 'rating' field of TaskStatus
        // describes how 'good', in terms of progress, each state is; the higher
        // the value, the higher the impact on a positive progess.
        // This allows us to sort the 'table' and to find the worst group with
        // the highest impact. - The idea behind this is that a task doesn't get flagged
        // as e.g. 'released' just because it has the same amount of items than a
        // worse group.
        return TaskStatus::find($table->keys()->first());
    }

    //--- All Tasks which require a status check
    public static function queue($bypassPermission = false) {

        $tasks = [];

        if(!\Laratrust::can('mark-as-updated-task') && !($bypassPermission)) {
            return $tasks;
        }

        $entries = \App\Task::where([
            ['updated_at', '<', \Carbon\Carbon::now()->subMinutes(1)->toDateTimeString()],
        ])->get();


        foreach($entries as $entry) {

            //--- Only non-released tasks
            if($entry->status()->id === 0) {
                continue;
            }

            $tasks[] = [
                'id'            => $entry->id,
                'name'          => $entry->name,
                'description'   => $entry->description,
                'status'        => $entry->status(),
                'type'          => $entry->type,
                'progress'      => $entry->progress,
                'standalone'    => $entry->standalone,
                'updated_at'    => $entry->updated_at,
                'created_at'    => $entry->created_at
            ];
        }

        return $tasks;
    }

    public function check(\App\User $user, $permissions) {

        //---
        if($user === null || $permissions === null) {
            return false;
        }

        $tmp = [];
        if(is_string($permissions)) {
            $tmp[] = $permissions;
        } else if(is_array($permissions)) {
            $tmp = $permissions;
        } else {
            return false;
        }

        foreach($permissions as $entry) {
            switch (strtolower($entry)) {
                case 'read': return $user->hasPermission('read-task');
                case 'update': return $user->hasPermission('update-task');
                case 'create': return $user->hasPermission('create-task');
                case 'delete': return $user->hasPermission('delete-task');
                case 'verify': return $user->hasPermission('verify-task');
                case 'bypass': return $user->hasPermission('bypass-visibility');

                default: return false;
            }
        }

    }

}
