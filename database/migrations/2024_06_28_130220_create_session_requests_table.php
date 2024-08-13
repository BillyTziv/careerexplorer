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
        Schema::create('session_requests', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->integer('age')->nullable();
            $table->integer('status')->default(0);
            $table->string('gender')->nullable();
            $table->string('career_status')->nullable();
            $table->string('expectations')->nullable();
            $table->string('notes')->nullable();
            $table->string('university')->nullable();
            $table->string('department')->nullable();
            $table->string('other_studies')->nullable();
            $table->bigInteger('assignee')->unsigned()->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE session_requests ADD CONSTRAINT fk_session_requests_users FOREIGN KEY (assignee) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_requests');
    }
};
