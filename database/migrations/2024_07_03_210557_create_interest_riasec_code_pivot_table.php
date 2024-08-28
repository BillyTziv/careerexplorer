<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterestRiasecCodePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('interest_riasec_code')) {
            Schema::create('interest_riasec_code', function (Blueprint $table) {
                $table->unsignedBigInteger('interest_id');
                $table->unsignedBigInteger('riasec_code_id');
                $table->timestamps();

                $table->primary(['interest_id', 'riasec_code_id']);

                $table->foreign('interest_id')
                    ->references('id')
                    ->on('interests')
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
        Schema::dropIfExists('interest_riasec_code');
    }
}
