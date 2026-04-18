<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = ['PG', 'SG', 'SF', 'PF', 'C'];

        $playersByTeam = [
            'Thunder Hawks' => [
                ['name' => 'Marcus Johnson', 'jersey_number' => 23, 'position' => 'PG'],
                ['name' => 'Darius Williams', 'jersey_number' => 11, 'position' => 'SG'],
                ['name' => 'Tyler Brooks', 'jersey_number' => 7, 'position' => 'SF'],
                ['name' => 'Kevin Anderson', 'jersey_number' => 32, 'position' => 'PF'],
                ['name' => 'Chris Martinez', 'jersey_number' => 44, 'position' => 'C'],
                ['name' => 'James Harper', 'jersey_number' => 5, 'position' => 'PG'],
                ['name' => 'Andre Mitchell', 'jersey_number' => 15, 'position' => 'SG'],
                ['name' => 'Brandon Lee', 'jersey_number' => 21, 'position' => 'SF'],
            ],
            'Fire Dragons' => [
                ['name' => 'Michael Chen', 'jersey_number' => 10, 'position' => 'PG'],
                ['name' => 'David Thompson', 'jersey_number' => 3, 'position' => 'SG'],
                ['name' => 'Jason Rivera', 'jersey_number' => 24, 'position' => 'SF'],
                ['name' => 'Eric Young', 'jersey_number' => 45, 'position' => 'PF'],
                ['name' => 'Robert Garcia', 'jersey_number' => 50, 'position' => 'C'],
                ['name' => 'Daniel White', 'jersey_number' => 8, 'position' => 'PG'],
                ['name' => 'Steven Clark', 'jersey_number' => 12, 'position' => 'SG'],
                ['name' => 'Anthony Hall', 'jersey_number' => 33, 'position' => 'PF'],
            ],
            'Storm Raiders' => [
                ['name' => 'Ryan Scott', 'jersey_number' => 1, 'position' => 'PG'],
                ['name' => 'Jacob Turner', 'jersey_number' => 22, 'position' => 'SG'],
                ['name' => 'Nathan Phillips', 'jersey_number' => 14, 'position' => 'SF'],
                ['name' => 'William Adams', 'jersey_number' => 35, 'position' => 'PF'],
                ['name' => 'Joshua Campbell', 'jersey_number' => 52, 'position' => 'C'],
                ['name' => 'Ethan Parker', 'jersey_number' => 6, 'position' => 'PG'],
                ['name' => 'Logan Evans', 'jersey_number' => 17, 'position' => 'SG'],
                ['name' => 'Mason Collins', 'jersey_number' => 25, 'position' => 'SF'],
            ],
            'Iron Wolves' => [
                ['name' => 'Alexander King', 'jersey_number' => 4, 'position' => 'PG'],
                ['name' => 'Benjamin Wright', 'jersey_number' => 13, 'position' => 'SG'],
                ['name' => 'Samuel Lopez', 'jersey_number' => 20, 'position' => 'SF'],
                ['name' => 'Henry Hill', 'jersey_number' => 40, 'position' => 'PF'],
                ['name' => 'Sebastian Moore', 'jersey_number' => 55, 'position' => 'C'],
                ['name' => 'Jack Taylor', 'jersey_number' => 9, 'position' => 'PG'],
                ['name' => 'Owen Thomas', 'jersey_number' => 18, 'position' => 'SG'],
                ['name' => 'Liam Jackson', 'jersey_number' => 27, 'position' => 'PF'],
            ],
            'Golden Eagles' => [
                ['name' => 'Noah Harris', 'jersey_number' => 2, 'position' => 'PG'],
                ['name' => 'Lucas Martin', 'jersey_number' => 16, 'position' => 'SG'],
                ['name' => 'Aiden Robinson', 'jersey_number' => 28, 'position' => 'SF'],
                ['name' => 'Carter Lewis', 'jersey_number' => 42, 'position' => 'PF'],
                ['name' => 'Jayden Walker', 'jersey_number' => 53, 'position' => 'C'],
                ['name' => 'Dylan Allen', 'jersey_number' => 0, 'position' => 'PG'],
                ['name' => 'Gabriel Young', 'jersey_number' => 19, 'position' => 'SG'],
                ['name' => 'Caleb Hernandez', 'jersey_number' => 31, 'position' => 'SF'],
            ],
            'Shadow Panthers' => [
                ['name' => 'Isaac Nelson', 'jersey_number' => 34, 'position' => 'PG'],
                ['name' => 'Connor Carter', 'jersey_number' => 26, 'position' => 'SG'],
                ['name' => 'Jordan Mitchell', 'jersey_number' => 29, 'position' => 'SF'],
                ['name' => 'Luke Perez', 'jersey_number' => 41, 'position' => 'PF'],
                ['name' => 'Aaron Roberts', 'jersey_number' => 54, 'position' => 'C'],
                ['name' => 'Hunter Turner', 'jersey_number' => 36, 'position' => 'PG'],
                ['name' => 'Eli Phillips', 'jersey_number' => 37, 'position' => 'SG'],
                ['name' => 'Adrian Campbell', 'jersey_number' => 38, 'position' => 'PF'],
            ],
            'Steel Titans' => [
                ['name' => 'Christian Parker', 'jersey_number' => 43, 'position' => 'PG'],
                ['name' => 'Jonathan Evans', 'jersey_number' => 46, 'position' => 'SG'],
                ['name' => 'Landon Edwards', 'jersey_number' => 47, 'position' => 'SF'],
                ['name' => 'Austin Collins', 'jersey_number' => 48, 'position' => 'PF'],
                ['name' => 'Chase Stewart', 'jersey_number' => 49, 'position' => 'C'],
                ['name' => 'Dominic Sanchez', 'jersey_number' => 51, 'position' => 'PG'],
                ['name' => 'Zachary Morris', 'jersey_number' => 56, 'position' => 'SG'],
                ['name' => 'Ian Rogers', 'jersey_number' => 57, 'position' => 'SF'],
            ],
            'Blazing Suns' => [
                ['name' => 'Jaxon Reed', 'jersey_number' => 58, 'position' => 'PG'],
                ['name' => 'Cooper Cook', 'jersey_number' => 59, 'position' => 'SG'],
                ['name' => 'Easton Morgan', 'jersey_number' => 60, 'position' => 'SF'],
                ['name' => 'Nolan Bailey', 'jersey_number' => 61, 'position' => 'PF'],
                ['name' => 'Asher Rivera', 'jersey_number' => 62, 'position' => 'C'],
                ['name' => 'Lincoln Cooper', 'jersey_number' => 63, 'position' => 'PG'],
                ['name' => 'Bentley Richardson', 'jersey_number' => 64, 'position' => 'SG'],
                ['name' => 'Camden Cox', 'jersey_number' => 65, 'position' => 'PF'],
            ],
        ];

        foreach ($playersByTeam as $teamName => $players) {
            $team = Team::where('name', $teamName)->first();
            
            if ($team) {
                foreach ($players as $playerData) {
                    Player::create([
                        'team_id' => $team->id,
                        'name' => $playerData['name'],
                        'jersey_number' => $playerData['jersey_number'],
                        'position' => $playerData['position'],
                    ]);
                }
            }
        }
    }
}
