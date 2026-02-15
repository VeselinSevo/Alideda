<?php

namespace Database\Seeders;
use App\Models\Store;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'is_admin' => true, // only if you added the column
            'password' => 'password',
        ]);

        // Customer users
        User::factory(10)->create();

        // Store owners + stores
        User::factory(3)->create()->each(function ($user) {
            Store::factory(rand(1, 3))->create([
                'user_id' => $user->id,
                'verified' => true,
            ]);
        });
    }
}
