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
        Schema::table('volunteers', function (Blueprint $table) {
            // Drop the existing foreign key that references the volunteers table
            $table->dropForeign(['assigned_to']);

            // Add the new foreign key to reference the users table
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            // Drop the foreign key that references the users table
            $table->dropForeign(['assigned_to']);

            // Restore the original foreign key referencing the volunteers table
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('volunteers')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
};
