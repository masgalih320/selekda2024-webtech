<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Galih Kopling',
            'username' => 'galih',
            'phone' => '085787725094',
            'email' => 'galih@kopling.com',
            'date_of_birth' => '1970-01-01 00:00:00',
            'password' => Hash::make('kopling'),
            'roles' => 'administrator',
            'email_verified_at' => now(),
            'created_at' => now(),
        ]);

        // Banner::factory(10)->create();
    }
}
