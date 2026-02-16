<?php

namespace Database\Seeders;
use App\Models\Country;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Po zelji moze i factory, ali posto je samo 2-3 zemlje, ovo je ok
        $countries = [
            ['code' => 'RS', 'name' => 'Serbia'],
            ['code' => 'ME', 'name' => 'Montenegro'],
            ['code' => 'HR', 'name' => 'Croatia'],
            // ...
        ];

        foreach ($countries as $c) {
            Country::updateOrCreate(
                ['code' => $c['code']],
                ['name' => $c['name']]
            );
        }
    }
}
