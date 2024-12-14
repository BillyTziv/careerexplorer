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
        DB::table('task_statuses')->insert([
            ['name' => 'todo', 'description' => 'Task has not yet started.', 'hex_color' => '#f59e0b', 'is_default' => true],
            ['name' => 'in-progress', 'description' => 'Task is currently being worked on.', 'hex_color' => '#2563eb'],
            ['name' => 'completed', 'description' => 'Task has been finished.', 'hex_color' => '#16a34a'],
        ]);
    }
}
