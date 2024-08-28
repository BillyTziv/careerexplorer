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
        if (!Schema::hasTable('career_skill')) {
            Schema::create('career_skill', function (Blueprint $table) {
                $table->unsignedBigInteger('career_id');
                $table->unsignedBigInteger('skill_id');
                $table->timestamps();

                $table->foreign('career_id')->references('id')->on('careers')->onDelete('cascade');
                $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');

                $table->primary(['career_id', 'skill_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_skill');
    }
};
