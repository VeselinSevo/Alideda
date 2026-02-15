<?php

namespace Database\Seeders;

use App\Models;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    //php artisan migrate:fresh --seed
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CountrySeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
