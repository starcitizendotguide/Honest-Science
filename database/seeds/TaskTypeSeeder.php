<?php

use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
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
                'name'  => 'Gameplay',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-gamepad'
                ]
            ],
            [
                'id'    => 1,
                'name'  => 'Engine',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-gears'
                ]
            ],
            [
                'id'    => 2,
                'name'  => 'Performance',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-dashboard'
                ]
            ],
            [
                'id'    => 3,
                'name'  => 'Celestial Body',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-globe'
                ]
            ],
            [
                'id'    => 4,
                'name'  => 'UI/UX',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-eye'
                ]
            ],
            [
                'id'    => 5,
                'name'  => 'Rework',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-refresh'
                ]
            ],
            [
                'id'    => 6,
                'name'  => 'Ships',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-space-shuttle'
                ]
            ],
            [
                'id'    => 7,
                'name'  => 'Ground Vehicles',
                'css'   => [
                    'class' => '',
                    'icon'  => 'fa fa-car'
                ]
            ]
        ];

        foreach ($statuses as $status) {
            $tmp = new App\TaskType;
            $tmp->id = $status['id'];
            $tmp->name = $status['name'];
            $tmp->css_class = $status['css']['class'];
            $tmp->css_icon = $status['css']['icon'];
            $tmp->save();
        }
    }
}
