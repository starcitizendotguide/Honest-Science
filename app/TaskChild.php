<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskChild extends Model
{

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'status',
        'progress',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The parent task of the child.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent() {
        return $this->hasOne('App\Task', 'id', 'task_id');
    }

    /**
     * The status of the child.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status() {
        return $this->hasOne('App\TaskStatus', 'id', 'status');
    }

    /**
     * The types of the child
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function types() {
        /**
        @param  string  $related
         * @param  string  $name
         * @param  string  $table
         * @param  string  $foreignPivotKey
         * @param  string  $relatedPivotKey
         * @param  string  $parentKey
         * @param  string  $relatedKey
         */
        return $this->belongsToMany('App\TaskType', 'task_children_types_map');
    }

    /**
     * The sources of the child.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sources() {
        return $this->hasMany('App\TaskSource', 'child_id', 'id');
    }

}
