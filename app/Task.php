<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\TaskStatus;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
        'visibility',
        'status',
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

    /**
     * The visibility of the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function visibility() {
        return $this->hasOne('App\Visibility', 'id', 'visibility');
    }

    /**
     * All children of the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany('App\TaskChild');
    }

    /**
     * The type of the task. Is null if it is not a standalone Task.
     *
     * @return mixed
     */
    public function types() {
        return $this->belongsToMany('App\TaskType', 'task_types_map');
    }

    /**
     * All sources of the task if it is a standalone Task, otherwise empty.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sources() {
        return $this->hasMany('App\TaskSource', 'parent_id', 'id');
    }

    /**
     * The status of the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status() {
        return $this->hasOne('App\TaskStatus', 'id', 'status');
    }

    /**
     * All tasks which require to be check by a member of the team if they are up-to-date or
     * deprecated.
     *
     * @return array
     */
    public static function deprecatedQueue() {

        $tasks = [];

        $entries = Task::where([
            ['updated_at', '<', Carbon::now()->subMonths(3)->toDateTimeString()],
            ['verified', '=', true],
        ])->get();

        foreach($entries as $entry) {

            //--- Only non-released tasks
            if($entry->status === 0) {
                continue;
            }

            $tasks[] = [
                'id'            => $entry->id,
                'name'          => $entry->name,
                'description'   => $entry->description,
                'status'        => $entry->status()->first(),
                'type'          => $entry->type,
                'progress'      => $entry->progress,
                'standalone'    => $entry->standalone,
                'updated_at'    => $entry->updated_at,
                'created_at'    => $entry->created_at
            ];
        }

        return $tasks;
    }

    /**
     * All tasks which were created by users which cannot verify tasks!
     *
     * @return array
     */
    public static function verifyQueue() {

        $tasks = [];

        $entries = Task::where([
            ['verified', '=', false],
        ])->get();

        foreach($entries as $entry) {

            $tasks[] = [
                'id'            => $entry->id,
                'name'          => $entry->name,
                'description'   => $entry->description,
                'status'        => $entry->status()->first(),
                'type'          => $entry->type,
                'progress'      => $entry->progress,
                'standalone'    => $entry->standalone,
                'updated_at'    => $entry->updated_at,
                'created_at'    => $entry->created_at
            ];

        }

        return $tasks;
    }


}
