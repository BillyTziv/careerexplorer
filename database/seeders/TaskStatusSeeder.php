<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name' => 'To Do',
                'description' => 'Tasks that need to be started.',
                'hex_color' => '#ef4444', // Red
                'is_default' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'In Progress',
                'description' => 'Tasks that are currently in progress.',
                'hex_color' => '#f59e0b', // Yellow
                'is_default' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Completed',
                'description' => 'Tasks that have been finished.',
                'hex_color' => '#10b981', // Green
                'is_default' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('task_statuses')->insert($statuses);
    }
}
