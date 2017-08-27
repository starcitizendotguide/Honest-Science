<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_statuses', function(Blueprint $table) {

            //--- Types
            $table->integer('id');
            $table->string('name');
            $table->double('rating');
            $table->string('css_class');
            $table->string('css_icon');

            $table->timestamps();

            //--- Index
            $table->primary('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task_statuses');
    }
}
