<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create(['name' => 'Marketing']);
        Team::create(['name' => 'HR']);
        Team::create(['name' => 'Development']);
        Team::create(['name' => 'Sales']);
        Team::create(['name' => 'Content Creation']);
        Team::create(['name' => 'Business']);
        Team::create(['name' => 'Career Coaching']);
        Team::create(['name' => 'Customer Service']);
        Team::create(['name' => 'Finance']);
        Team::create(['name' => 'Legal']);
        Team::create(['name' => 'Management']);
    }
}
