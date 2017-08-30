<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\TaskStatus;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
        'status',
        'type',
        'progress',
        'standalone'
    ];

    protected $casts = [
        'standalone' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function scopeById($query, $id) {
        return $query->where('id', $id);
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
            return TaskStatus::byId($this->status)->first();
        }

        $DEFAULT_STATUS = 3;

        //--- No children is or should be invalid, so we return the default status
        $children = null;

        if(!($children = $this->children())->exists()) {
            return TaskStatus::byId($DEFAULT_STATUS)->first();
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
        return TaskStatus::byId($table->keys()->first())->first();
    }

    //--- All Tasks which require a status check
    public static function queue() {

        $tasks = [];

        $entries = \App\Task::where([
            ['updated_at', '<', \Carbon\Carbon::now()->subMonths(1.5)->toDateTimeString()]
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

}
