<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\UserManagement\Permission;
use App\Models\UserManagement\User;
use App\Models\UserManagement\Role;

use Exception;

use Faker\Factory as Faker;
use Carbon\Carbon;

class SessionRequestsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $now = Carbon::now();
        $startDate = $now->copy()->subMonths(5)->startOfMonth();
        $endDate = $now->endOfMonth();

        $sessionRequests = [];

        // Ensure each month has at least 5 records
        for ($i = 0; $i < 6; $i++) {
            $monthStart = $startDate->copy()->addMonths($i);
            $monthEnd = $monthStart->copy()->endOfMonth();

            for ($j = 0; $j < 5; $j++) {
                $sessionRequests[] = [
                    'firstname' => $faker->firstName,
                    'lastname' => $faker->lastName,
                    'email' => $faker->unique()->safeEmail,
                    'phone' => $faker->phoneNumber,
                    'age' => $faker->numberBetween(18, 65),
                    'status' => $faker->numberBetween(0, 1),
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'career_status' => $faker->jobTitle,
                    'expectations' => $faker->sentence,
                    'notes' => $faker->sentence,
                    'university' => $faker->company,
                    'department' => $faker->word,
                    'other_studies' => $faker->word,
                    'assignee' => null,
                    'created_at' => $faker->dateTimeBetween($monthStart, $monthEnd),
                    'updated_at' => now(),
                ];
            }
        }

        // Add remaining records to reach 100
        for ($i = count($sessionRequests); $i < 100; $i++) {
            $randomDate = $faker->dateTimeBetween($startDate, $endDate);
            $sessionRequests[] = [
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'age' => $faker->numberBetween(18, 65),
                'status' => $faker->numberBetween(0, 1),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'career_status' => $faker->jobTitle,
                'expectations' => $faker->sentence,
                'notes' => $faker->sentence,
                'university' => $faker->company,
                'department' => $faker->word,
                'other_studies' => $faker->word,
                'assignee' => null,
                'created_at' => $randomDate,
                'updated_at' => now(),
            ];
        }

        DB::table('session_requests')->insert($sessionRequests);
    }
}