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
        if (!Schema::hasTable('career_interest')) {
            Schema::create('career_interest', function (Blueprint $table) {
                $table->unsignedBigInteger('career_id');
                $table->unsignedBigInteger('interest_id');
                $table->timestamps();

                $table->foreign('career_id')->references('id')->on('careers')->onDelete('cascade');
                $table->foreign('interest_id')->references('id')->on('interests')->onDelete('cascade');

                $table->primary(['career_id', 'interest_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_interest');
    }
};
