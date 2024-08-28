<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerResponsibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('career_responsibilities')) {
            Schema::create('career_responsibilities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('career_id');
                $table->text('text');
                $table->timestamps();

                $table->foreign('career_id')->references('id')->on('careers')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_responsibilities');
    }
}
