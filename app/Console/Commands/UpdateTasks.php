<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all tasks.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //--- All non-standalone tasks since standalone tasks have a status value assigned
        // by hand to them.
        $tasks = \App\Task::where('standalone', '=', false)->get();

        $DEFAULT_STATUS = \App\TaskStatus::find(3);

        $_count = 0;

        foreach ($tasks as $task) {

            //--- No children is or should be invalid, so we return the default status
            $children = null;

            if(!($children = $task->children())->exists()) {
                \DB::table('tasks')->where('id', $task->id)->update(['status' => $DEFAULT_STATUS->id]);
                $_count++;
                continue;
            }

            //--- Score
            $children = $children->get();
            $table = [];

            $children->each(function($item, $key) use (&$table) {
                $status = $item->status()->first();

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
            // the value, the higher the impact on a positive progress.
            // This allows us to sort the 'table' and to find the worst group with
            // the highest impact. - The idea behind this is that a task doesn't get flagged
            // as e.g. 'released' just because it has the same amount of items than a
            // worse group.
            $statusId = \App\TaskStatus::find($table->keys()->first())->id;
            \DB::table('tasks')->where('id', $task->id)->update(['status' => $statusId]);
            $_count++;
            //---

        }

        //--- Log
        $logEntry = new \App\LogEntry;
        $logEntry->entry    = 'update_tasks';
        $logEntry->name     = 'Update Tasks';
        $logEntry->message  = sprintf('Updated %d tasks.', $_count);
        $logEntry->logged   = \Carbon\Carbon::now();
        $logEntry->save();

        //@TODO Hack - Delete old log entries

    }

}
