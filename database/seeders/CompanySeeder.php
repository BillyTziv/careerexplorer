<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Career Explorer Inc.',
            'description' => 'A leading company in career guidance and professional training.',
            'website_url' => 'https://careerexplorer.gr',
            'logo' => 'career_explorer_logo.png',
            'contact_email' => 'info@careerexplorer.com',
            'phone_number' => '+0000000000',
            'address' => '123 Career St, Professional City, PC 12345',
            'establishment_year' => 2010,
            'industry' => 'Education & Training',
            'deleted' => 0,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}