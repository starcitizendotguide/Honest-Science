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
                    'icon'  => 'fa fa-gamepad'
                ]
            ],
            [
                'id'    => 1,
                'name'  => 'Engine',
                'css'   => [
                    'icon'  => 'fa fa-gears'
                ]
            ],
            [
                'id'    => 2,
                'name'  => 'Performance',
                'css'   => [
                    'icon'  => 'fa fa-dashboard'
                ]
            ],
            [
                'id'    => 3,
                'name'  => 'Celestial Body',
                'css'   => [
                    'icon'  => 'fa fa-globe'
                ]
            ],
            [
                'id'    => 4,
                'name'  => 'UI/UX',
                'css'   => [
                    'icon'  => 'fa fa-eye'
                ]
            ],
            [
                'id'    => 5,
                'name'  => 'Rework',
                'css'   => [
                    'icon'  => 'fa fa-refresh'
                ]
            ],
            [
                'id'    => 6,
                'name'  => 'Ships',
                'css'   => [
                    'icon'  => 'fa fa-space-shuttle'
                ]
            ],
            [
                'id'    => 7,
                'name'  => 'Ground Vehicles',
                'css'   => [
                    'icon'  => 'fa fa-car'
                ]
            ],
            [
                'id'    => 8,
                'name'  => 'Spectrum',
                'css'   => [
                    'icon'  => 'fa fa-commenting'
                ]
            ]
        ];

        foreach ($statuses as $status) {
            $tmp = new App\TaskType;
            $tmp->id = $status['id'];
            $tmp->name = $status['name'];
            $tmp->css_icon = $status['css']['icon'];
            $tmp->save();
        }
    }
}
