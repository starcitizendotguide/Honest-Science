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
                'id'    => -1,
                'name'  => 'Unknown',
                'rating'=> -1,
                'css'   => [
                            'class' => '',
                            'icon'  => ''
                        ]
            ],
            [
                'id'    => 0,
                'name'  => 'Released',
                'rating'=> 5,
                'css'   => 'task-released',
                'css'   => [
                            'class' => 'task-released',
                            'icon'  => 'fa fa-battery-4'
                        ]
            ],
            [
                'id'    => 1,
                'name'  => 'Partially Released',
                'rating'=> 4,
                'css'   => [
                            'class' => 'task-partially-released',
                            'icon'  => 'fa fa-battery-3'
                        ]
            ],
            [
                'id'    => 2,
                'name'  => 'In-Progress',
                'rating'=> 3,
                'css'   => [
                            'class' => 'task-in-progress',
                            'icon'  => 'fa fa-battery-2'
                        ]
            ],
            [
                'id'    => 3,
                'name'  => 'Stagnant',
                'rating'=> 2,
                'css'   => [
                            'class' => 'task-stagnant',
                            'icon'  => 'fa fa-battery-1'
                        ]
            ],
            [
                'id'    => 4,
                'name'  => 'Cut/Broken',
                'rating'=> 1,
                'css'   => [
                            'class' => 'task-broken',
                            'icon'  => 'fa fa-chain-broken'
                        ]
            ]
        ];

        foreach ($statuses as $status) {
            $tmp = new App\TaskStatus;
            $tmp->id = $status['id'];
            $tmp->name = $status['name'];
            $tmp->rating = $status['rating'];
            $tmp->css_class = $status['css']['class'];
            $tmp->css_icon = $status['css']['icon'];
            $tmp->save();
        }
    }
}
