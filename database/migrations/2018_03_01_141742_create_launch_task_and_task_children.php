<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaunchTaskAndTaskChildren extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function($table) {
            $table->boolean('post_launch')->default(false);
        });
        Schema::table('task_children', function($table) {
            $table->boolean('post_launch')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function($table) {
            $table->dropColumn('post_launch');
        });
        Schema::table('task_children', function($table) {
            $table->dropColumn('post_launch');
        });
    }
}
