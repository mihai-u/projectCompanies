<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('title');
            $table->string('description');
            $table->integer('status_id')->default('In Progress');
            $table->unsignedBigInteger('owner_id');
            // $table->foreign('owner_id')->references('id')->on('users');
            $table->unsignedBigInteger('responsible_id');
            // $table->foreign('responsible_id')->references('id')->on('users');
            $table->unsignedBigInteger('client_id');
            // $table->foreign('client_id')->references('id')->on('users');
            $table->unsignedBigInteger('company_id');
            // $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('project_id');
            // $table->foreign('project_id')->references('id')->on('projects');
            $table->time('worked_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
