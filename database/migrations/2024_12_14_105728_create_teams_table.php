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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // e.g., 'Marketing', 'HR', 'Development'
            $table->text('description')->nullable();    // Optional: description of the team
            $table->timestamps();
        });

        DB::table('teams')->insert([ 'name' => 'Marketing', 'description' => 'This is the Marketing team.' ]);
        DB::table('teams')->insert([ 'name' => 'Graphic Design', 'description' => 'This is the Graphic Design team.' ]);
        DB::table('teams')->insert([ 'name' => 'Copywriting', 'description' => 'This is the Copywriting team.' ]);
        DB::table('teams')->insert([ 'name' => 'Core', 'description' => 'This is the Core team.' ]);
        DB::table('teams')->insert([ 'name' => 'Social Media Content Creators', 'description' => 'This is the Social Media Content Creators team.' ]);
        DB::table('teams')->insert([ 'name' => 'Content Editors', 'description' => 'This is the Content Editors team.' ]);
        DB::table('teams')->insert([ 'name' => 'Career Coaches', 'description' => 'This is the Career Coaches team.' ]);
        DB::table('teams')->insert([ 'name' => 'Software Developers', 'description' => 'This is the Software Developers team.' ]);
        DB::table('teams')->insert([ 'name' => 'Translators', 'description' => 'This is the Translators team.' ]);
        DB::table('teams')->insert([ 'name' => 'Career Insights Coordinator', 'description' => 'This is the Career Insights Coordinator team.' ]);
        DB::table('teams')->insert([ 'name' => 'Recruiters', 'description' => 'This is the Recruiters team.' ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
