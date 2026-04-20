<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\GameMatch;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerStat;
use App\Models\PlayoffSlot;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\TeamRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PublicController extends Controller
{
    /**
     * Display the match schedule
     */
    public function schedule()
    {
        return redirect()->route('home', ['section' => 'schedule']);
    }

    /**
     * Display team rankings
     */
    public function rankings()
    {
        return redirect()->route('home', ['section' => 'rankings']);
    }

    /**
     * Display team stats
     */
    public function teamStats()
    {
        return redirect()->route('home', ['section' => 'team-stats']);
    }

    /**
     * Display individual player stats
     */
    public function individualStats()
    {
        return redirect()->route('home', ['section' => 'individual-stats']);
    }

    /**
     * Display a single player's stats
     */
    public function playerStats(Player $player)
    {
        $player->load(['team.division']);

        $playerTotals = PlayerStat::query()
            ->where('player_id', $player->id)
            ->where('played', true)
            ->selectRaw('COUNT(DISTINCT match_id) as games_played')
            ->selectRaw('COALESCE(SUM(points), 0) as total_points')
            ->selectRaw('COALESCE(SUM(assists), 0) as total_assists')
            ->selectRaw('COALESCE(SUM(rebounds), 0) as total_rebounds')
            ->selectRaw('COALESCE(SUM(steals), 0) as total_steals')
            ->selectRaw('COALESCE(SUM(blocks), 0) as total_blocks')
            ->selectRaw('COALESCE(MAX(points), 0) as highest_points')
            ->first();

        $gamesPlayed = (int) ($playerTotals->games_played ?? 0);

        $radarRows = PlayerStat::query()
            ->where('played', true)
            ->selectRaw('player_id')
            ->selectRaw('COUNT(DISTINCT match_id) as games_played')
            ->selectRaw('COALESCE(SUM(points), 0) as total_points')
            ->selectRaw('COALESCE(SUM(assists), 0) as total_assists')
            ->selectRaw('COALESCE(SUM(rebounds), 0) as total_rebounds')
            ->selectRaw('COALESCE(SUM(steals), 0) as total_steals')
            ->selectRaw('COALESCE(SUM(blocks), 0) as total_blocks')
            ->selectRaw('COALESCE(MAX(points), 0) as highest_points')
            ->groupBy('player_id')
            ->get();

        $radarMaxes = [
            'points_per_game' => 0,
            'assists_per_game' => 0,
            'rebounds_per_game' => 0,
            'steals_per_game' => 0,
            'blocks_per_game' => 0,
        ];

        foreach ($radarRows as $row) {
            $rowGamesPlayed = (int) ($row->games_played ?? 0);
            if ($rowGamesPlayed <= 0) {
                continue;
            }

            $radarMaxes['points_per_game'] = max(
                $radarMaxes['points_per_game'],
                ((float) $row->total_points) / $rowGamesPlayed
            );
            $radarMaxes['assists_per_game'] = max(
                $radarMaxes['assists_per_game'],
                ((float) $row->total_assists) / $rowGamesPlayed
            );
            $radarMaxes['rebounds_per_game'] = max(
                $radarMaxes['rebounds_per_game'],
                ((float) $row->total_rebounds) / $rowGamesPlayed
            );
            $radarMaxes['steals_per_game'] = max(
                $radarMaxes['steals_per_game'],
                ((float) $row->total_steals) / $rowGamesPlayed
            );
            $radarMaxes['blocks_per_game'] = max(
                $radarMaxes['blocks_per_game'],
                ((float) $row->total_blocks) / $rowGamesPlayed
            );
        }

        $playerData = [
            'id' => $player->id,
            'name' => $player->name,
            'photo' => $player->photo,
            'team' => $player->team?->name,
            'team_id' => $player->team_id,
            'division_id' => $player->team?->division_id,
            'division' => $player->team?->division?->name,
            'jersey_number' => $player->jersey_number,
            'position' => $player->position,
            'games_played' => $gamesPlayed,
            'total_points' => (int) ($playerTotals->total_points ?? 0),
            'total_assists' => (int) ($playerTotals->total_assists ?? 0),
            'total_rebounds' => (int) ($playerTotals->total_rebounds ?? 0),
            'total_steals' => (int) ($playerTotals->total_steals ?? 0),
            'total_blocks' => (int) ($playerTotals->total_blocks ?? 0),
            'highest_points' => (int) ($playerTotals->highest_points ?? 0),
            'points_per_game' => $gamesPlayed > 0
                ? round(((float) $playerTotals->total_points) / $gamesPlayed, 2)
                : 0,
            'assists_per_game' => $gamesPlayed > 0
                ? round(((float) $playerTotals->total_assists) / $gamesPlayed, 2)
                : 0,
            'rebounds_per_game' => $gamesPlayed > 0
                ? round(((float) $playerTotals->total_rebounds) / $gamesPlayed, 2)
                : 0,
            'steals_per_game' => $gamesPlayed > 0
                ? round(((float) $playerTotals->total_steals) / $gamesPlayed, 2)
                : 0,
            'blocks_per_game' => $gamesPlayed > 0
                ? round(((float) $playerTotals->total_blocks) / $gamesPlayed, 2)
                : 0,
        ];

        return Inertia::render('Public/PlayerStats', [
            'player' => $playerData,
            'radarMaxes' => $radarMaxes,
        ]);
    }

    /**
     * Display highest points leaderboard
     */
    public function highestPoints()
    {
        return redirect()->route('home', ['section' => 'highest-points']);
    }

    /**
     * Display events
     */
    public function events()
    {
        $events = Event::with('author')
            ->where('is_published', true)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->get();

        return Inertia::render('Public/Events', [
            'events' => $events,
        ]);
    }

    /**
     * Display announcements
     */
    public function announcements()
    {
        $announcements = Announcement::with('author')
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return Inertia::render('Public/Announcements', [
            'announcements' => $announcements
        ]);
    }

    /**
     * Display welcome/home page
     */
    public function home()
    {
        $section = request()->query('section', 'dashboard');

        $allowedSections = [
            'dashboard',
            'schedule',
            'rankings',
            'team-stats',
            'individual-stats',
            'highest-points',
            'standings',
        ];

        if (!in_array($section, $allowedSections, true)) {
            $section = 'dashboard';
        }

        $upcomingMatches = GameMatch::with(['teamA', 'teamB'])
            ->where('status', 'upcoming')
            ->orderBy('match_date', 'asc')
            ->limit(7)
            ->get();

        $recentAnnouncements = Announcement::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $topTeams = Team::orderBy('points', 'desc')
            ->limit(5)
            ->get();
        $offSeason = \App\Models\Setting::where('key', 'off_season')->value('value') === 'true';

        $registrationOpen = \App\Models\Setting::where('key', 'registration_open')->value('value') !== 'false';
        $registrationClosingDate = \App\Models\Setting::where('key', 'registration_closing_date')->value('value');
        $teamRegistrationOpen = \App\Models\Setting::where('key', 'team_registration_open')->value('value') !== 'false';
        $teamRegistrationClosingDate = \App\Models\Setting::where('key', 'team_registration_closing_date')->value('value');

        if ($registrationOpen && $registrationClosingDate) {
            if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($registrationClosingDate))) {
                $registrationOpen = false;
            }
        }

        if ($teamRegistrationOpen && $teamRegistrationClosingDate) {
            if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($teamRegistrationClosingDate))) {
                $teamRegistrationOpen = false;
            }
        }

        $divisions = Division::all();

        $matches = [];
        $rankingsTeams = [];
        $teamStatsTeams = [];
        $individualStatsPlayers = [];
        $highestPointsPlayers = [];
        $bracketsByDivision = [];

        $dashboardMatches = [];
        $dashboardLiveMatches = [];
        $dashboardStandingsTeams = [];
        $dashboardLeagueLeaders = [];
        $dashboardPlayerStats = [];

        if ($section === 'dashboard') {
            $dashboardLiveMatches = GameMatch::with(['teamA', 'teamB'])
                ->whereIn('status', ['live', 'ongoing'])
                ->orderBy('match_date', 'desc')
                ->limit(6)
                ->get();

            $tonightMatches = GameMatch::with(['teamA', 'teamB'])
                ->whereDate('match_date', \Carbon\Carbon::today())
                ->where('status', 'upcoming')
                ->orderBy('match_date', 'asc')
                ->limit(4)
                ->get();

            $dashboardMatches = $tonightMatches->isNotEmpty()
                ? $tonightMatches
                : $upcomingMatches->take(4);

            $dashboardStandingsTeams = Team::with('division')
                ->orderBy('wins', 'desc')
                ->orderBy('points', 'desc')
                ->limit(10)
                ->get();

            $statRows = PlayerStat::query()
                ->where('played', true)
                ->selectRaw('player_id')
                ->selectRaw('COUNT(DISTINCT match_id) as games_played')
                ->selectRaw('COALESCE(SUM(points), 0) as total_points')
                ->selectRaw('COALESCE(SUM(assists), 0) as total_assists')
                ->selectRaw('COALESCE(SUM(rebounds), 0) as total_rebounds')
                ->selectRaw('COALESCE(SUM(steals), 0) as total_steals')
                ->selectRaw('COALESCE(SUM(blocks), 0) as total_blocks')
                ->selectRaw('COALESCE(SUM(fouls), 0) as total_fouls')
                ->groupBy('player_id')
                ->get();

            $playersById = Player::with(['team.division'])
                ->whereIn('id', $statRows->pluck('player_id'))
                ->get()
                ->keyBy('id');

            $dashboardPlayerStats = $statRows
                ->map(function ($row) use ($playersById) {
                    $player = $playersById->get($row->player_id);
                    if (!$player || !$player->team) {
                        return null;
                    }

                    $gamesPlayed = (int) ($row->games_played ?? 0);
                    if ($gamesPlayed <= 0) {
                        return null;
                    }

                    $totalPoints = (int) ($row->total_points ?? 0);
                    $totalAssists = (int) ($row->total_assists ?? 0);
                    $totalRebounds = (int) ($row->total_rebounds ?? 0);
                    $totalSteals = (int) ($row->total_steals ?? 0);
                    $totalBlocks = (int) ($row->total_blocks ?? 0);
                    $totalFouls = (int) ($row->total_fouls ?? 0);

                    $ppg = round($totalPoints / $gamesPlayed, 1);
                    $apg = round($totalAssists / $gamesPlayed, 1);
                    $rpg = round($totalRebounds / $gamesPlayed, 1);
                    $spg = round($totalSteals / $gamesPlayed, 1);
                    $bpg = round($totalBlocks / $gamesPlayed, 1);

                    $effTotal = $totalPoints + $totalRebounds + $totalAssists + $totalSteals + $totalBlocks - $totalFouls;
                    $eff = round($effTotal / $gamesPlayed, 1);

                    return [
                        'id' => $player->id,
                        'name' => $player->name,
                        'photo' => $player->photo,
                        'team' => $player->team?->name,
                        'team_id' => $player->team_id,
                        'division_id' => $player->team?->division_id,
                        'games_played' => $gamesPlayed,
                        'ppg' => $ppg,
                        'apg' => $apg,
                        'rpg' => $rpg,
                        'spg' => $spg,
                        'bpg' => $bpg,
                        'eff' => $eff,
                    ];
                })
                ->filter()
                ->values();

            $pointsLeader = $dashboardPlayerStats->sortByDesc('ppg')->first();
            $assistsLeader = $dashboardPlayerStats->sortByDesc('apg')->first();
            $reboundsLeader = $dashboardPlayerStats->sortByDesc('rpg')->first();

            $leaderSpecs = [
                [
                    'key' => 'ppg',
                    'label' => 'POINTS PER GAME',
                    'tone' => 'gold',
                    'leader' => $pointsLeader,
                ],
                [
                    'key' => 'apg',
                    'label' => 'ASSISTS PER GAME',
                    'tone' => 'blue',
                    'leader' => $assistsLeader,
                ],
                [
                    'key' => 'rpg',
                    'label' => 'REBOUNDS PER GAME',
                    'tone' => 'green',
                    'leader' => $reboundsLeader,
                ],
            ];

            foreach ($leaderSpecs as $spec) {
                $leader = $spec['leader'];
                if (!$leader) {
                    continue;
                }

                $sparkRows = PlayerStat::query()
                    ->where('player_id', $leader['id'])
                    ->where('played', true)
                    ->orderByDesc('match_id')
                    ->limit(10)
                    ->get()
                    ->reverse();

                $sparkline = $sparkRows->map(function ($row) use ($spec) {
                    $key = $spec['key'];
                    $col = $key === 'ppg' ? 'points' : ($key === 'apg' ? 'assists' : 'rebounds');
                    return (int) ($row->{$col} ?? 0);
                })->values();

                $dashboardLeagueLeaders[] = [
                    'key' => $spec['key'],
                    'label' => $spec['label'],
                    'tone' => $spec['tone'],
                    'value' => $leader[$spec['key']],
                    'player' => [
                        'id' => $leader['id'],
                        'name' => $leader['name'],
                        'team' => $leader['team'],
                        'photo' => $leader['photo'] ?? null,
                    ],
                    'sparkline' => $sparkline,
                ];
            }
        }

        if ($section === 'schedule') {
            $matches = GameMatch::with(['teamA', 'teamB'])
                ->orderBy('match_date', 'asc')
                ->get();
        }

        if ($section === 'rankings') {
            $rankingsTeams = Team::with('division')
                ->orderBy('points', 'desc')
                ->orderBy('wins', 'desc')
                ->get()
                ->map(function ($team, $index) {
                    $team->rank = $index + 1;
                    return $team;
                });
        }

        if ($section === 'team-stats') {
            $teamStatsTeams = Team::with('division')
                ->withCount('players')
                ->with(['players' => function ($query) {
                    $query->withCount('stats');
                }])
                ->get();
        }

        if ($section === 'individual-stats') {
            $individualStatsPlayers = Player::with(['team.division', 'stats'])
                ->get()
                ->map(function ($player) {
                    $playedStats = $player->stats->where('played', true);

                    return [
                        'id' => $player->id,
                        'name' => $player->name,
                        'photo' => $player->photo,
                        'team' => $player->team->name,
                        'team_id' => $player->team_id,
                        'division_id' => $player->team->division_id,
                        'jersey_number' => $player->jersey_number,
                        'position' => $player->position,
                        'games_played' => $playedStats->unique('match_id')->count(),
                        'total_points' => $playedStats->sum('points'),
                        'total_assists' => $playedStats->sum('assists'),
                        'total_rebounds' => $playedStats->sum('rebounds'),
                        'highest_points' => $playedStats->max('points') ?? 0,
                    ];
                });
        }

        if ($section === 'highest-points') {
            $highestPointsPlayers = Player::with(['team.division', 'stats'])
                ->get()
                ->map(function ($player) {
                    $playedStats = $player->stats->where('played', true);
                    $gamesPlayed = $playedStats->unique('match_id')->count();

                    $totalPoints = (int) $playedStats->sum('points');
                    $totalAssists = (int) $playedStats->sum('assists');
                    $totalRebounds = (int) $playedStats->sum('rebounds');
                    $totalSteals = (int) $playedStats->sum('steals');
                    $totalBlocks = (int) $playedStats->sum('blocks');

                    // Weighted ranking using overall totals only.
                    $rankingScore = round(
                        ($totalAssists * 0.35) +
                        ($totalPoints * 0.25) +
                        ($totalRebounds * 0.20) +
                        ($totalSteals * 0.12) +
                        ($totalBlocks * 0.08),
                        2
                    );

                    return [
                        'id' => $player->id,
                        'name' => $player->name,
                        'photo' => $player->photo,
                        'team' => $player->team->name,
                        'team_id' => $player->team_id,
                        'division_id' => $player->team->division_id,
                        'jersey_number' => $player->jersey_number,
                        'highest_points' => $playedStats->max('points') ?? 0,
                        'games_played' => $gamesPlayed,
                        'total_points' => $totalPoints,
                        'total_assists' => $totalAssists,
                        'total_rebounds' => $totalRebounds,
                        'total_steals' => $totalSteals,
                        'total_blocks' => $totalBlocks,
                        'ranking_score' => $rankingScore,
                    ];
                })
                ->sort(function (array $a, array $b) {
                    return [
                        $b['ranking_score'],
                        $b['total_assists'],
                        $b['total_points'],
                        $b['total_rebounds'],
                    ] <=> [
                        $a['ranking_score'],
                        $a['total_assists'],
                        $a['total_points'],
                        $a['total_rebounds'],
                    ];
                })
                ->values();
        }

        if ($section === 'standings') {
            // Build bracket data per division (only divisions that have slots)
            foreach ($divisions as $division) {
                $slots = PlayoffSlot::with('team')
                    ->where('division_id', $division->id)
                    ->orderBy('bracket_side')
                    ->orderBy('round')
                    ->orderBy('position')
                    ->get();

                if ($slots->isEmpty()) {
                    continue;
                }

                $bracketsByDivision[] = [
                    'division' => $division,
                    'upperBracket' => $slots->where('bracket_side', 'upper')->values(),
                    'lowerBracket' => $slots->where('bracket_side', 'lower')->values(),
                ];
            }
        }
        
        return Inertia::render('Public/Home', [
            'section' => $section,
            'upcomingMatches' => $upcomingMatches,
            'recentAnnouncements' => $recentAnnouncements,
            'topTeams' => $topTeams,
            'isOffSeason' => $offSeason,
            'registrationOpen' => $registrationOpen,
            'registrationClosingDate' => $registrationClosingDate,
            'teamRegistrationOpen' => $teamRegistrationOpen,
            'teamRegistrationClosingDate' => $teamRegistrationClosingDate,
            'divisions' => $divisions,
            'matches' => $matches,
            'rankingsTeams' => $rankingsTeams,
            'teamStatsTeams' => $teamStatsTeams,
            'individualStatsPlayers' => $individualStatsPlayers,
            'highestPointsPlayers' => $highestPointsPlayers,
            'bracketsByDivision' => $bracketsByDivision,
            'dashboardMatches' => $dashboardMatches,
            'dashboardLiveMatches' => $dashboardLiveMatches,
            'dashboardStandingsTeams' => $dashboardStandingsTeams,
            'dashboardLeagueLeaders' => $dashboardLeagueLeaders,
            'dashboardPlayerStats' => $dashboardPlayerStats,
        ]);
    }

    /**
     * Display match details with MVP
     */
    public function matchDetails(GameMatch $match)
    {
        $match->load(['teamA', 'teamB']);
        
        // Get all player stats for this match
        $playerStats = PlayerStat::with(['player.team'])
            ->where('match_id', $match->id)
            ->get();
        
        // Separate stats by team
        $teamAStats = $playerStats->filter(function ($stat) use ($match) {
            return $stat->player->team_id === $match->team_a_id;
        })->values();
        
        $teamBStats = $playerStats->filter(function ($stat) use ($match) {
            return $stat->player->team_id === $match->team_b_id;
        })->values();
        
        // Determine MVP (player with highest points from winning team)
        $mvp = null;
        if ($match->status === 'finished') {
            $winningTeamId = null;
            if ($match->team_a_score > $match->team_b_score) {
                $winningTeamId = $match->team_a_id;
            } elseif ($match->team_b_score > $match->team_a_score) {
                $winningTeamId = $match->team_b_id;
            }
            
            if ($winningTeamId) {
                $mvp = $playerStats
                    ->filter(function ($stat) use ($winningTeamId) {
                        return $stat->player->team_id === $winningTeamId;
                    })
                    ->sortByDesc('points')
                    ->first();
            }
        }
        
        return Inertia::render('Public/MatchDetails', [
            'match' => $match,
            'teamAStats' => $teamAStats,
            'teamBStats' => $teamBStats,
            'mvp' => $mvp,
        ]);
    }

    /**
     * Display public standings/bracket page
     */
    public function standings()
    {
        return redirect()->route('home', ['section' => 'standings']);
    }

    /**
     * Display about page
     */
    public function about()
    {
        return Inertia::render('Public/About');
    }

    /**
     * Display public player registration form
     */
    public function playerRegistrationForm()
    {
        $registrationOpen = \App\Models\Setting::where('key', 'registration_open')->value('value') !== 'false';
        $registrationClosingDate = \App\Models\Setting::where('key', 'registration_closing_date')->value('value');

        // Check if registration is officially closed (either manually or past closing date)
        $isClosed = !$registrationOpen;
        if ($registrationOpen && $registrationClosingDate) {
            if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($registrationClosingDate))) {
                $isClosed = true;
            }
        }

        if ($isClosed) {
            return redirect()->route('home')->with('error', 'Player registration is officially closed.');
        }

        $divisions = Division::all();
        $teams = Team::with('division')->get();
        
        return Inertia::render('Public/PlayerRegister', [
            'divisions' => $divisions,
            'teams' => $teams,
        ]);
    }

    /**
     * Handle public player registration
     */
    public function submitPlayerRegistration(Request $request)
    {
        $registrationOpen = \App\Models\Setting::where('key', 'registration_open')->value('value') !== 'false';
        $registrationClosingDate = \App\Models\Setting::where('key', 'registration_closing_date')->value('value');

        $isClosed = !$registrationOpen;
        if ($registrationOpen && $registrationClosingDate) {
            if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($registrationClosingDate))) {
                $isClosed = true;
            }
        }

        if ($isClosed) {
            return back()->with('error', 'Player registration is officially closed.');
        }

        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'jersey_number' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('players')->where('team_id', $request->team_id)
            ],
            'position' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:5120', // 5MB max
        ], [
            'jersey_number.unique' => 'This jersey number is already taken in the selected team.',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $destination = public_path('uploads/players');

            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $filename = $photo->hashName();
            $photo->move($destination, $filename);
            $validated['photo'] = "/uploads/players/{$filename}";
        }

        Player::create($validated);

        return back()->with('success', 'Player registered successfully!');
    }

    /**
     * Display public team registration form
     */
    public function teamRegistrationForm()
    {
        $teamRegistrationOpen = \App\Models\Setting::where('key', 'team_registration_open')->value('value') !== 'false';
        $teamRegistrationClosingDate = \App\Models\Setting::where('key', 'team_registration_closing_date')->value('value');

        $isClosed = !$teamRegistrationOpen;
        if ($teamRegistrationOpen && $teamRegistrationClosingDate) {
            if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($teamRegistrationClosingDate))) {
                $isClosed = true;
            }
        }

        if ($isClosed) {
            return redirect()->route('home')->with('error', 'Team registration is officially closed.');
        }

        $divisions = Division::all();

        return Inertia::render('Public/TeamRegister', [
            'divisions' => $divisions,
        ]);
    }

    /**
     * Handle public team registration
     */
    public function submitTeamRegistration(Request $request)
    {
        $teamRegistrationOpen = \App\Models\Setting::where('key', 'team_registration_open')->value('value') !== 'false';
        $teamRegistrationClosingDate = \App\Models\Setting::where('key', 'team_registration_closing_date')->value('value');

        $isClosed = !$teamRegistrationOpen;
        if ($teamRegistrationOpen && $teamRegistrationClosingDate) {
            if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($teamRegistrationClosingDate))) {
                $isClosed = true;
            }
        }

        if ($isClosed) {
            return back()->with('error', 'Team registration is officially closed.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $destination = public_path('uploads/teams');

            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $filename = $logo->hashName();
            $logo->move($destination, $filename);
            $validated['logo'] = "/uploads/teams/{$filename}";
        }

        TeamRegistrationRequest::create([
            'name' => $validated['name'],
            'division_id' => $validated['division_id'] ?? null,
            'logo' => $validated['logo'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Team registration request submitted! Pending admin approval.');
    }
}
