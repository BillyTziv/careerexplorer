<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('task_logs')) {
            Schema::create('task_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_id')
                      ->constrained('tasks')
                      ->onDelete('cascade');
                $table->timestamp('start_time');
                $table->timestamp('end_time')->nullable();
                $table->integer('duration')->nullable(); // Duration in minutes
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
        Schema::dropIfExists('task_logs');
    }
}
