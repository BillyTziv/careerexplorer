<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('volunteers', function (Blueprint $table) {
            // Adding new social media column for TikTok
            $table->string('tiktok')->nullable()->after('linkedin');
            
            // Adding new fields for volunteering details
            $table->integer('hour_per_week')->nullable()->after('role');
            $table->text('previous_volunteering')->nullable()->after('hour_per_week');
            $table->text('volunteering_details')->nullable()->after('previous_volunteering');

            // Optional: Adding a JSON column to store additional volunteer info (future-proofing)
            $table->json('additional_info')->nullable()->after('volunteering_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('volunteers', function (Blueprint $table) {
            // Dropping the new columns on rollback
            $table->dropColumn('tiktok');
            $table->dropColumn('hour_per_week');
            $table->dropColumn('previous_volunteering');
            $table->dropColumn('volunteering_details');
            $table->dropColumn('additional_volunteer_info');
        });
    }
}
