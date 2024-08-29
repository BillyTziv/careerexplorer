<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerValueRiasecCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('career_value_riasec_code')) {
            Schema::create('career_value_riasec_code', function (Blueprint $table) {
                $table->unsignedBigInteger('career_value_id');
                $table->unsignedBigInteger('riasec_code_id');
                $table->timestamps();

                $table->primary(['career_value_id', 'riasec_code_id']);

                $table->foreign('career_value_id')
                    ->references('id')
                    ->on('career_values')
                    ->onDelete('cascade');

                $table->foreign('riasec_code_id')
                    ->references('id')
                    ->on('riasec_codes')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('career_value_riasec_code');
    }
}
