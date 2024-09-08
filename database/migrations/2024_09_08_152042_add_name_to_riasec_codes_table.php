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
        if (!Schema::hasColumn('riasec_codes', 'name')) {
            Schema::table('riasec_codes', function (Blueprint $table) {
                $table->string('name')->after('id');
            });

            // Populate the 'name' column with the value from the 'code' column
            DB::table('riasec_codes')->update([
                'name' => DB::raw('code')
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('riasec_codes', 'name')) {
            Schema::table('riasec_codes', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
