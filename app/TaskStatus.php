<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Task;
use App\TaskChild;

class TaskStatus extends Model
{

    protected $fillable = [
        'id',
        'name',
        'rating',
        'css_class',
        'css_icon'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Returns the relative amount of tasks in the specific TaskStatus, but
     * it excludes tasks which are e.g. not visible to user!
     *
     * @return float|int
     */
    public function countRelative() {

        //@TODO Fix -> Only count Task and TaskChilds the user can see!

        $count = TaskChild::count();

        if($count === 0) {
            return 0;
        }

        $childTotal = TaskChild::where([
            ['status', '=', $this->id],
        ])->count();
        $childCount = TaskChild::count();

        $standaloneTotal = Task::where([
            ['status', '=', $this->id],
            ['standalone', '=', true],
            ['verified', '=', true],
            ['visibility', '>', -1]
        ])->count();
        $standaloneCount = Task::where('standalone', '=', true)->count();

        return (($childTotal + $standaloneTotal) / ($childCount + $standaloneCount));
    }

}
