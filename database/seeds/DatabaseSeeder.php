<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(LaratrustSeeder::class);
        $this->call(TaskStatusSeeder::class);
        $this->call(TaskTypeSeeder::class);
        $this->call(VisibilitySeeder::class);

        if(\App::environment('local')) {
            $this->call(TasksSeeder::class);
            $this->call(DebugSeeder::class);
        }

    }
}
