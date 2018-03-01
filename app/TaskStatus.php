<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Task;
use App\TaskChild;
use Illuminate\Support\Facades\DB;

class TaskStatus extends Model
{

    protected $fillable = [
        'id',
        'name',
        'rating',
        'css_class',
        'css_icon',
        'value_min',
        'value_max'
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

        //@TODO Move to Command so it can be scheduled and wont cause 4 queries every time its called (happens often).
        //@TODO Fix -> Only count Task and TaskChilds the user can see!

        $count = TaskChild::count();

        if($count === 0) {
            return 0;
        }

        $childCount = DB::select('SELECT COUNT(task_children.id) AS `childCount`
                FROM task_children
                  LEFT JOIN tasks ON tasks.id = task_children.id
                WHERE tasks.count_progress_as_one = FALSE AND task_children.status = ' . $this->id)[0]->childCount;
        $childTotal = DB::select('SELECT COUNT(task_children.id) AS `childTotal`
                FROM task_children
                  LEFT JOIN tasks ON tasks.id = task_children.id
                WHERE tasks.count_progress_as_one = FALSE')[0]->childTotal;

        $taskCount = DB::select('SELECT COUNT(tasks.id) AS `taskCount` 
                      FROM tasks
                    WHERE tasks.verified = TRUE AND tasks.visibility > -1 AND (tasks.standalone = TRUE OR (tasks.count_progress_as_one = TRUE AND tasks.standalone = false))
                      AND tasks.status = ' . $this->id)[0]->taskCount;
        $taskTotal = DB::select('SELECT COUNT(tasks.id) AS `taskTotal`
                  FROM tasks
                WHERE (tasks.standalone = TRUE OR (tasks.count_progress_as_one = TRUE AND tasks.standalone = false)) AND tasks.visibility > -1 AND tasks.verified = TRUE;')[0]->taskTotal;
        $result = (($childCount + $taskCount) / ($childTotal + $taskTotal));
        return $result;
    }

}
