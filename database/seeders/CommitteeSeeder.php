<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Committee Member',
            'email' => 'committee@example.com',
            'password' => Hash::make('password'),
            'role' => 'committee',
        ]);

        User::create([
            'name' => 'John Committee',
            'email' => 'john.committee@example.com',
            'password' => Hash::make('password'),
            'role' => 'committee',
        ]);
    }
}
