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
        if (!Schema::hasTable('task_statuses')) {
            Schema::create('task_statuses', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();                               // Status name
                $table->text('description')->nullable();                        // Description of the status
                $table->string('hex_color', 7)->default('#84cc16');             // Status color
                $table->unsignedBigInteger('email_template_id')->nullable();    // Optional email template association
                $table->boolean('is_default')->default(false);                  // Indicates default status
                $table->boolean('is_builtin')->default(false);                  // Indicates system parameter
                $table->softDeletes();                                          // Soft delete support
                $table->timestamps();

                $table->foreign('email_template_id')
                    ->references('id')
                    ->on('email_templates')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            });

            // Default Statuses Todo, In Progress, Done
            DB::table('task_statuses')->insert([
                ['name' => 'Todo', 'description' => 'Task is not started yet', 'hex_color' => '#ff0000', 'is_default' => true, 'is_builtin' => true],
                ['name' => 'In Progress', 'description' => 'Task is in progress', 'hex_color' => '#ffcc00', 'is_default' => false, 'is_builtin' => true],
                ['name' => 'Done', 'description' => 'Task is completed', 'hex_color' => '#00ff00', 'is_default' => false, 'is_builtin' => true],
            ]);

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_statuses');
    }
};
