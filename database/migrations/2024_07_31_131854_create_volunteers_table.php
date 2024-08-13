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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->integer('status')->default(1);
            $table->longText('description')->nullable();
            $table->longText('expectations')->nullable();
            $table->longText('interests')->nullable();
            $table->longText('reason')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('university')->nullable();
            $table->string('department')->nullable();
            $table->string('otherstudies')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('role')->nullable();
            $table->longText('notes')->nullable();
            $table->string('asana')->nullable();
            $table->string('google_drive')->nullable();
            $table->text('disapproved_reason')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->longText('cv')->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_role')->nullable();
            $table->unsignedInteger('hours_contributed')->default(0);
            $table->boolean('onboarding_completed')->default(false);
            $table->text('previous_volunteer_experience')->nullable();
            $table->integer('years_experience')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->string('career_status')->nullable();
            $table->boolean('cv_consent')->default(false);
            
            $table->foreign('role')->references('id')->on('volunteer_roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('assigned_to')->references('id')->on('volunteers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
