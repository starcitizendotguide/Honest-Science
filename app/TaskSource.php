<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskSource extends Model
{

    protected $table = 'task_sources';

    protected $fillable = [
        'child_id',
        'parent_id',
        'link',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The parent the source belongs to. This can either be a TaskChild,
     * if the task is not standalone, and a Task if it parent task is
     * a standalone task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent() {
        if($this->child_id === null) {
            return $this->hasOne('App\Task', 'id', 'parent_id');
        } else if($this->parent_id === null) {
            return $this->hasOne('App\TaskChild', 'id', 'child_id');
        }
    }

}
