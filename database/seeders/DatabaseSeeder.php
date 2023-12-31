<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Aloy',
            'email' => 'aloy@dailyreportyahya.com',
            'password' => bcrypt('rusakdeh'), // Ganti dengan kata sandi yang aman
            'role' => 'kepala',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@dailyreportyahya.com',
            'password' => bcrypt('rusakdeh'), // Ganti dengan kata sandi yang aman
            'role' => 'admin',
        ]);
    }
}
