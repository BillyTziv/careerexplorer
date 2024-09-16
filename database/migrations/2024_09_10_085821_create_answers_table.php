<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('answers')) {
            Schema::create('answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('question_id');
                $table->string('answer');
                $table->timestamps();
                $table->unsignedBigInteger('submission_id')->nullable();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');

                $table->foreign('question_id')
                    ->references('id')
                    ->on('questions');

                $table->foreign('submission_id')
                    ->references('id')
                    ->on('submissions')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
