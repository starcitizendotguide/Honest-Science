<?php

use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'id'    => 0,
                'name'  => 'Released',
                'css'   => 'task-released'
            ],
            [
                'id'    => 1,
                'name'  => 'Partially Released',
                'css'   => 'task-partially-released'
            ],
            [
                'id'    => 2,
                'name'  => 'In-Progress',
                'css'   => 'task-in-progress'
            ],
            [
                'id'    => 3,
                'name'  => 'Stagnant',
                'css'   => 'task-stagnant'
            ],
            [
                'id'    => 4,
                'name'  => 'Broken',
                'css'   => 'task-broken'
            ],
            [
                'id'    => 5,
                'name'  => 'Cut',
                'css'   => 'task-cut'
            ]
        ];

        foreach ($statuses as $status) {
            $tmp = new App\TaskStatus;
            $tmp->id = $status['id'];
            $tmp->name = $status['name'];
            $tmp->css = $status['css'];
            $tmp->save();
        }
    }
}
