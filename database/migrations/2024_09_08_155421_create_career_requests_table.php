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
        if (!Schema::hasTable('career_requests')) {
            Schema::create('career_requests', function (Blueprint $table) {
                $table->id();
                $table->string('email')->nullable();
                $table->string('keyword');
                $table->integer('status')->default(1); // 0: Pending, 1: Completed, 2: Deleted
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_requests');
    }
};
