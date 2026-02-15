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
        // laravel kad pozovemo Country model i staticku metodu factory, on ce automatski da zna da treba da koristi CountryFactory klasu(ptrazice je u factory folderu- ime vazno da se podudara konvenciski)
        Country::factory()->count(10)->create();
    }
}
