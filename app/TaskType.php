<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{

    public function taskChildren() {
        return $this->hasMany('App\TaskChildren', 'type', 'id');
    }

}
