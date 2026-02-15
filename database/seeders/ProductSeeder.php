<?php

namespace Database\Seeders;
use App\Models\Store;
use App\Models\Product;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::all()->each(function ($store) {
            Product::factory()
                ->count(2)
                ->for($store)
                ->create();
        });
    }
}
