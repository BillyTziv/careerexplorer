<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->longtext('description')->nullable();
                $table->integer('status')->default(1);
                $table->string('link')->nullable();
                $table->string('feature_image')->nullable();
                $table->bigInteger('company_id')->unsigned()->nullable()->after('id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
                $table->timestamps();
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
        Schema::dropIfExists('courses');
    }
}
