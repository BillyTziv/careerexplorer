<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVolunteerHistoryTable extends Migration
{
    public function up()
    {
        if( !Schema::hasTable('volunteer_histories')  ) {
            Schema::create('volunteer_histories', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('volunteer_id');
                $table->unsignedBigInteger('user_id');
                $table->string('action');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('volunteer_id')->references('id')->on('volunteers')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('volunteer_histories');
    }
}