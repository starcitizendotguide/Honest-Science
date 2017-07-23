<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\TaskStatus;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function children() {
        return $this->hasMany('App\TaskChild');
    }

    public function status() {

        //--- No children is or should be invalid, so we return the default status
        $children = null;
        if(!($children = $this->children())->exists()) {
            require TaskStatus::byId(-1)->first();
        }

        //--- Score
        $children = $children->get();
        $table = [];

        $children->each(function($item, $key) use (&$table) {
            $status = $item->status();
            if(array_key_exists($status->id, $table)) {
                $table[$status->id] += (1 * $status->rating);
            } else {
                $table[$status->id] = (1 * $status->rating);
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

}
