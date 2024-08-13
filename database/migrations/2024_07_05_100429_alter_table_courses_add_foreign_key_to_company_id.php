<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCoursesAddForeignKeyToCompanyId extends Migration
{   
    /**
    * Run the migrations.
    */
    public function up()
    {
        // Add the company_id column
        DB::statement("ALTER TABLE courses ADD COLUMN company_id BIGINT UNSIGNED NULL AFTER id");

        // Add the foreign key constraint
        DB::statement("ALTER TABLE courses ADD CONSTRAINT fk_courses_company_id FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop the foreign key constraint
        DB::statement("ALTER TABLE courses DROP FOREIGN KEY fk_courses_company_id");

        // Drop the company_id column
        DB::statement("ALTER TABLE courses DROP COLUMN company_id");
    }
}
