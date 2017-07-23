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
                'css'   => [
                            'class' => '',
                            'icon'  => ''
                        ]
            ],
            [
                'id'    => 0,
                'name'  => 'Released',
                'css'   => 'task-released',
                'css'   => [
                            'class' => 'button task-released',
                            'icon'  => 'fa fa-battery-4'
                        ]
            ],
            [
                'id'    => 1,
                'name'  => 'Partially Released',
                'css'   => [
                            'class' => 'button task-partially-released',
                            'icon'  => 'fa fa-battery-3'
                        ]
            ],
            [
                'id'    => 2,
                'name'  => 'In-Progress',
                'css'   => [
                            'class' => 'button task-in-progress',
                            'icon'  => 'fa fa-battery-2'
                        ]
            ],
            [
                'id'    => 3,
                'name'  => 'Stagnant',
                'css'   => [
                            'class' => 'button task-stagnant',
                            'icon'  => 'fa fa-battery-1'
                        ]
            ],
            [
                'id'    => 4,
                'name'  => 'Cut/Broken',
                'css'   => [
                            'class' => 'button task-broken',
                            'icon'  => 'fa fa-chain-broken'
                        ]
            ]
        ];

        foreach ($statuses as $status) {
            $tmp = new App\TaskStatus;
            $tmp->id = $status['id'];
            $tmp->name = $status['name'];
            $tmp->css_class = $status['css']['class'];
            $tmp->css_icon = $status['css']['icon'];
            $tmp->save();
        }
    }
}
