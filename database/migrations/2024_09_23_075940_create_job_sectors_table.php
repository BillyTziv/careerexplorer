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
        Schema::create('job_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();    // Adding the description field
            $table->string('icon')->nullable();         // Adding the icon field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_sectors');
    }
};
