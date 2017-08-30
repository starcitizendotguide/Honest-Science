<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStandaloneTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function($table) {

            $table->boolean('standalone')->default(false);
            $table->integer('status')->nullable();
            $table->integer('type')->nullable();
            $table->double('progress')->nullable();

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

            $table->dropColumn('standalone');
            $table->dropColumn('status');
            $table->dropColumn('type');
            $table->dropColumn('progress');

        });
    }
}
