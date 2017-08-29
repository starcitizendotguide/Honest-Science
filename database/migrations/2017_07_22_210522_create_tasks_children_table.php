<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_children', function(Blueprint $table) {

            //--- Types
            $table->increments('id')->index();
            $table->integer('task_id')->unsigned();

            $table->string('name');
            $table->text('description');

            $table->integer('status');
            $table->integer('type');
            $table->double('progress');

            $table->timestamps();

        });

        Schema::table('task_children', function($table) {
            $table->foreign('task_id')->references('id')->on('tasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task_children');
    }
}
