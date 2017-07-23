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
        $this->call(TasksSeeder::class);

        if(env('APP_DEBUG') === true) {
            $this->call(DebugSeeder::class);
        }

    }
}
