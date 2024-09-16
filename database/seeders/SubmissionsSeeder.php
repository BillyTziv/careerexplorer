<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\UserManagement\User;
use App\Models\Test\Test;

class SubmissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Seed users if needed
        if (User::count() === 0) {
            User::factory()->count(20)->create(); // Using User factory to create 20 users
        }

        // Seed tests if needed
        if (Test::count() === 0) {
            for ($i = 1; $i <= 10; $i++) {
                DB::table('tests')->insert([
                    'name' => 'Test ' . $i,
                    'description' => $faker->sentence,
                    'category' => 'Category ' . $i,
                    'type' => 1,
                    'deleted' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Now seed the submissions table
        for ($i = 1; $i <= 50; $i++) {
            DB::table('submissions')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'test_id' => Test::inRandomOrder()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
