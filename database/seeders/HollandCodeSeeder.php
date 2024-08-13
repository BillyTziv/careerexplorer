<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career\RiasecCode;

class HollandCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = [
            [
                'name' => 'Πρακτικός',
                'description' => 'Περιγραφή για Πρακτικός',
                'code' => 'realistic',
                'symbol' => 'R',
            ],
            [
                'name' => 'Ερευνητικός',
                'description' => 'Περιγραφή για Ερευνητικός',
                'code' => 'investigative',
                'symbol' => 'I',
            ],
            [
                'name' => 'Καλλιτεχνικός',
                'description' => 'Περιγραφή για Καλλιτεχνικός',
                'code' => 'artistic',
                'symbol' => 'A',
            ],
            [
                'name' => 'Κοινωνικός',
                'description' => 'Περιγραφή για Κοινωνικός',
                'code' => 'social',
                'symbol' => 'S',
            ],
            [
                'name' => 'Επιχειρηματικός',
                'description' => 'Περιγραφή για Επιχειρηματικός',
                'code' => 'enterprising',
                'symbol' => 'E',
            ],
            [
                'name' => 'Συμβατικός',
                'description' => 'Περιγραφή για Συμβατικός',
                'code' => 'conventional',
                'symbol' => 'C',
            ],
        ];

        foreach ($codes as $code) {
            RiasecCode::create([
                'name' => $code['name'],
                'code' => $code['code'],
                'symbol' => $code['symbol'],
                'description' => $code['description'] ?? null,
            ]);
        }
    }
}
