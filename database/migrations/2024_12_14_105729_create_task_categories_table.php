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
        Schema::create('task_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // e.g., 'Development', 'Marketing', etc.
            $table->text('description')->nullable();  // Optional: description of the category
            $table->timestamps();
        });

        // Insert a default category after creating the table
        DB::table('task_categories')->insert([
            'name' => 'Default Category',
            'description' => 'This is the default category assigned to tasks without specific categories.',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_categories');
    }
};
