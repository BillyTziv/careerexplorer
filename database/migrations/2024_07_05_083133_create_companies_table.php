<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('website_url')->nullable();
                $table->string('logo')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('phone_number')->nullable();
                $table->text('address')->nullable();
                $table->year('establishment_year')->nullable();
                $table->string('industry')->nullable();
                $table->boolean("deleted")->default(false);

                $table->rememberToken();
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
        Schema::dropIfExists('companies');
    }
}
