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
        if (!Schema::hasTable('career_values')) {
            Schema::create('career_values', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('status')->default(0);
                $table->text('description')->nullable();
                $table->boolean('deleted')->default(false);
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_values');
    }
};
