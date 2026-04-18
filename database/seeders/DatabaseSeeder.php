<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create committee user
        User::factory()->create([
            'name' => 'Committee User',
            'email' => 'committee@example.com',
            'password' => bcrypt('password'),
            'role' => 'committee',
        ]);

        // Run other seeders
        $this->call([
            TeamSeeder::class,
            PlayerSeeder::class,
        ]);
        
        // Settings
        \App\Models\Setting::firstOrCreate(
            ['key' => 'off_season'],
            ['value' => 'false']
        );
    }
}
