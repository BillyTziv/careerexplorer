<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('career_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();
    
            // Foreign key constraints
            $table->foreign('career_id')
                ->references('id')->on('careers')
                ->onDelete('cascade');
            
            $table->foreign('course_id')
                ->references('id')->on('courses')
                ->onDelete('cascade');
    
            // Ensure the combination is unique
            $table->unique(['career_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_course');
    }
}
