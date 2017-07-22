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
        Schema::create('tasks_children', function(Blueprint $table) {

            //--- Types
            $table->increments('id');
            $table->integer('parent_id');

            $table->string('name');
            $table->text('description');

            $table->smallInteger('status_id');
            $table->integer('type');
            $table->double('progress');


            $table->timestamps();

            //--- Index
            $table->index('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks_children');
    }
}
