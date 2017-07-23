<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

        //@TODO The status is based on the completion of all sub tasks

        //--- No children is or should be invalid, so we return the default status
        $children = null;
        if(!($children = $this->children())->exists()) {
            require TaskStatus::byId(-1)->first();
        }

        $children = $children->get();

        $children->each(function($item, $key) {
            dd($item);
        });

        return TaskStatus::byId(1)->first();
    }

}
