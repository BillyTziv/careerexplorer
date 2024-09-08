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
        if( !Schema::hasTable('questions')  ) {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('type');
                $table->string('description')->nullable();
                $table->bigInteger('test_id')->unsigned()->nullable();
                $table->timestamps();

                $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
