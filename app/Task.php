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
    public function type() {
        return $this->hasOne('App\TaskType', 'id', 'type')->first();
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
     * @return \Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     */
    public function status() {

        //--- Standalone Status
        if($this->standalone === true) {
            return TaskStatus::find($this->status);
        }

        //if(\Cache::has($CACHE_KEY)) {
        //    return \Cache::get($CACHE_KEY);
        //}

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

    /**
     * All tasks which require to be check by a member of the team if they are up-to-date or
     * deprecated.
     *
     * @return array
     */
    public static function deprecatedQueue() {

        $tasks = [];

        $entries = \App\Task::where([
            ['updated_at', '<', \Carbon\Carbon::now()->subMinutes(1)->toDateTimeString()],
            ['verified', '=', true],
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

    /**
     * All tasks which were created by users which cannot verify tasks!
     *
     * @return array
     */
    public static function verifyQueue() {

        $tasks = [];

        $entries = \App\Task::where([
            ['verified', '=', false],
        ])->get();

        foreach($entries as $entry) {

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


}
