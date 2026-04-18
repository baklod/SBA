<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Thunder Hawks',
                'logo' => null,
                'wins' => 12,
                'losses' => 3,
                'points' => 156,
            ],
            [
                'name' => 'Fire Dragons',
                'logo' => null,
                'wins' => 10,
                'losses' => 5,
                'points' => 142,
            ],
            [
                'name' => 'Storm Raiders',
                'logo' => null,
                'wins' => 9,
                'losses' => 6,
                'points' => 135,
            ],
            [
                'name' => 'Iron Wolves',
                'logo' => null,
                'wins' => 8,
                'losses' => 7,
                'points' => 128,
            ],
            [
                'name' => 'Golden Eagles',
                'logo' => null,
                'wins' => 7,
                'losses' => 8,
                'points' => 121,
            ],
            [
                'name' => 'Shadow Panthers',
                'logo' => null,
                'wins' => 6,
                'losses' => 9,
                'points' => 114,
            ],
            [
                'name' => 'Steel Titans',
                'logo' => null,
                'wins' => 5,
                'losses' => 10,
                'points' => 107,
            ],
            [
                'name' => 'Blazing Suns',
                'logo' => null,
                'wins' => 3,
                'losses' => 12,
                'points' => 93,
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
