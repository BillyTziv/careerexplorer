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

                $table->string('task_name');
                $table->text('description')->nullable();

                $table->integer('priority')->default(1);            // 1: Low, 2: Medium, 3: High, 4: Critical
                $table->integer('points')->default(0);              // Points for the task

                $table->date('completed_date')->nullable();
                $table->date('due_date')->nullable();

                $table->integer('estimated_time')->nullable();      // Time in minutes
                $table->integer('actual_time')->nullable();         // Time in minutes

                $table->timestamps();

                // Team
                $table->foreignId('team_id')
                    ->constrained('teams')
                    ->onDelete('cascade');

                // Category
                $table->foreignId('category_id')
                    ->constrained('task_categories')
                    ->onDelete('cascade');

                // Assignee
                $table->foreignId('volunteer_id')
                    ->constrained('volunteers')
                    ->onDelete('cascade');

                // Status
                $table->foreignId('status_id')
                    ->constrained('task_statuses')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
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
