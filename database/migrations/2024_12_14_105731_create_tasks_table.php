<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('volunteer_id')
                    ->constrained('volunteers')
                    ->onDelete('cascade');
                $table->string('task_name');
                $table->text('description')->nullable();
                $table->integer('estimated_time')->nullable(); // Time in minutes
                $table->integer('actual_time')->nullable();    // Time in minutes
                $table->foreignId('status_id')
                    ->constrained('task_statuses')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
                $table->timestamps();
            });
        }
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
