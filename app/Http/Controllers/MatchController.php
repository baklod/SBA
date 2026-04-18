<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerStat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchController extends Controller
{
    /**
     * Display a listing of matches
     */
    public function index()
    {
        $matches = GameMatch::with(['teamA', 'teamB'])
            ->orderBy('match_date', 'desc')
            ->get();
        
        // Check if request is from committee routes
        if (request()->routeIs('committee.*')) {
            return Inertia::render('Committee/Matches/Index', [
                'matches' => $matches
            ]);
        }
        
        return Inertia::render('Admin/Matches/Index', [
            'matches' => $matches
        ]);
    }

    /**
     * Display the specified match
     */
    public function show(GameMatch $match)
    {
        $match->load(['teamA', 'teamB', 'playerStats.player']);
        
        return Inertia::render('Committee/Matches/Show', [
            'match' => $match
        ]);
    }

    /**
     * Show the form for creating a new match
     */
    public function create()
    {
        $teams = Team::all();
        
        return Inertia::render('Admin/Matches/Create', [
            'teams' => $teams
        ]);
    }

    /**
     * Store a newly created match
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_a_id' => 'required|exists:teams,id',
            'team_b_id' => 'required|exists:teams,id|different:team_a_id',
            'match_date' => 'required|date',
            'match_time' => 'nullable|date_format:H:i',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,paused,finished',
        ]);

        GameMatch::create($validated);

        return redirect()->route('admin.matches.index')
            ->with('success', 'Match created successfully.');
    }

    /**
     * Show the form for editing the specified match
     */
    public function edit(GameMatch $match)
    {
        $teams = Team::all();
        
        // Check if request is from committee routes
        if (request()->routeIs('committee.*')) {
            return Inertia::render('Committee/Matches/Edit', [
                'match' => $match->load(['teamA', 'teamB']),
                'teams' => $teams
            ]);
        }
        
        return Inertia::render('Admin/Matches/Edit', [
            'match' => $match->load(['teamA', 'teamB']),
            'teams' => $teams
        ]);
    }

    /**
     * Update the specified match
     */
    public function update(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'team_a_id' => 'required|exists:teams,id',
            'team_b_id' => 'required|exists:teams,id|different:team_a_id',
            'match_date' => 'required|date',
            'match_time' => 'nullable|date_format:H:i',
            'venue' => 'nullable|string|max:255',
            'team_a_score' => 'nullable|integer|min:0',
            'team_b_score' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,ongoing,paused,finished',
        ]);

        $match->update($validated);

        // Update team records if match is finished
        if ($validated['status'] === 'finished' && isset($validated['team_a_score']) && isset($validated['team_b_score'])) {
            $this->updateTeamRecords($match);
        }

        // Redirect based on route
        if (request()->routeIs('committee.*')) {
            return redirect()->route('committee.matches.index')
                ->with('success', 'Match updated successfully.');
        }

        return redirect()->route('admin.matches.index')
            ->with('success', 'Match updated successfully.');
    }

    /**
     * Remove the specified match
     */
    public function destroy(GameMatch $match)
    {
        $match->delete();

        return redirect()->route('admin.matches.index')
            ->with('success', 'Match deleted successfully.');
    }

    /**
     * Show the live scoring process page
     */
    public function process(GameMatch $match)
    {
        $match->load(['teamA.players', 'teamB.players', 'playerStats']);
        
        // Get existing stats for this match
        $existingStats = $match->playerStats->keyBy('player_id');
        
        // Add current stats to players
        $teamAPlayers = $match->teamA->players->map(function ($player) use ($existingStats) {
            $stat = $existingStats->get($player->id);
            $player->current_points = $stat ? $stat->points : 0;
            $player->current_fouls = $stat ? $stat->fouls : 0;
            $player->current_assists = $stat ? $stat->assists : 0;
            $player->current_rebounds = $stat ? $stat->rebounds : 0;
            $player->current_blocks = $stat ? $stat->blocks : 0;
            $player->current_steals = $stat ? $stat->steals : 0;
            $player->current_played = $stat ? (bool) $stat->played : false;
            $player->stat_id = $stat ? $stat->id : null;
            return $player;
        });
        
        $teamBPlayers = $match->teamB->players->map(function ($player) use ($existingStats) {
            $stat = $existingStats->get($player->id);
            $player->current_points = $stat ? $stat->points : 0;
            $player->current_fouls = $stat ? $stat->fouls : 0;
            $player->current_assists = $stat ? $stat->assists : 0;
            $player->current_rebounds = $stat ? $stat->rebounds : 0;
            $player->current_blocks = $stat ? $stat->blocks : 0;
            $player->current_steals = $stat ? $stat->steals : 0;
            $player->current_played = $stat ? (bool) $stat->played : false;
            $player->stat_id = $stat ? $stat->id : null;
            return $player;
        });
        
        return Inertia::render('Committee/Matches/Process', [
            'match' => $match,
            'teamAPlayers' => $teamAPlayers,
            'teamBPlayers' => $teamBPlayers,
        ]);
    }

    /**
     * Add score to a player (AJAX)
     */
    public function addScore(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'points' => 'required|integer|in:-3,-2,-1,1,2,3',
        ]);

        $player = Player::find($validated['player_id']);
        
        // Find or create player stat for this match
        $stat = PlayerStat::firstOrCreate(
            [
                'match_id' => $match->id,
                'player_id' => $validated['player_id'],
            ],
            [
                'points' => 0,
                'rebounds' => 0,
                'assists' => 0,
                'steals' => 0,
                'blocks' => 0,
                'fouls' => 0,
                'played' => true,
            ]
        );

        if (!$stat->played) {
            $stat->update(['played' => true]);
        }

        $points = $validated['points'];
        
        // Prevent negative total points
        if ($points < 0 && $stat->points + $points < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reduce points below 0',
            ]);
        }

        // Add/subtract points
        $stat->increment('points', $points);

        // Update match score based on player's team
        if ($player->team_id == $match->team_a_id) {
            // Prevent negative team score
            if ($points < 0 && $match->team_a_score + $points < 0) {
                // Rollback player stat
                $stat->decrement('points', abs($points));
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reduce team score below 0',
                ]);
            }
            $match->increment('team_a_score', $points);
        } else {
            // Prevent negative team score
            if ($points < 0 && $match->team_b_score + $points < 0) {
                // Rollback player stat
                $stat->decrement('points', abs($points));
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reduce team score below 0',
                ]);
            }
            $match->increment('team_b_score', $points);
        }

        // Return updated data
        return response()->json([
            'success' => true,
            'player_points' => $stat->points,
            'team_a_score' => $match->fresh()->team_a_score,
            'team_b_score' => $match->fresh()->team_b_score,
        ]);
    }

    /**
     * Add foul to a player (AJAX)
     */
    public function addFoul(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'action' => 'sometimes|in:add,subtract',
        ]);

        // Find or create player stat for this match
        $stat = PlayerStat::firstOrCreate(
            [
                'match_id' => $match->id,
                'player_id' => $validated['player_id'],
            ],
            [
                'points' => 0,
                'rebounds' => 0,
                'assists' => 0,
                'fouls' => 0,
                'blocks' => 0,
                'steals' => 0,
                'played' => true,
            ]
        );

        if (!$stat->played) {
            $stat->update(['played' => true]);
        }

        $action = $validated['action'] ?? 'add';

        if ($action === 'subtract') {
            // Prevent going below 0
            if ($stat->fouls <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reduce fouls below 0',
                ]);
            }
            $stat->decrement('fouls');
        } else {
            $stat->increment('fouls');
        }

        return response()->json([
            'success' => true,
            'player_fouls' => $stat->fouls,
        ]);
    }

    /**
     * Add stat (assist, rebound, block, steal) to a player (AJAX)
     */
    public function addStat(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'stat_type' => 'required|in:assists,rebounds,blocks,steals',
            'action' => 'sometimes|in:add,subtract',
        ]);

        // Find or create player stat for this match
        $stat = PlayerStat::firstOrCreate(
            [
                'match_id' => $match->id,
                'player_id' => $validated['player_id'],
            ],
            [
                'points' => 0,
                'rebounds' => 0,
                'assists' => 0,
                'fouls' => 0,
                'blocks' => 0,
                'steals' => 0,
                'played' => true,
            ]
        );

        if (!$stat->played) {
            $stat->update(['played' => true]);
        }

        $statType = $validated['stat_type'];
        $action = $validated['action'] ?? 'add';

        if ($action === 'subtract') {
            // Prevent going below 0
            if ($stat->{$statType} <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reduce stat below 0',
                ]);
            }
            $stat->decrement($statType);
        } else {
            $stat->increment($statType);
        }

        return response()->json([
            'success' => true,
            'stat_type' => $statType,
            'stat_value' => $stat->{$statType},
        ]);
    }

    /**
     * Mark a player as played/not played for this match (AJAX)
     */
    public function setPlayerPlayed(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'played' => 'required|boolean',
        ]);

        $player = Player::findOrFail($validated['player_id']);
        $playerTeamId = (int) ($player->team_id ?? 0);

        if ($playerTeamId !== (int) $match->team_a_id && $playerTeamId !== (int) $match->team_b_id) {
            abort(403, 'Player is not part of this match');
        }

        $played = (bool) $validated['played'];

        $stat = PlayerStat::query()
            ->where('match_id', $match->id)
            ->where('player_id', $player->id)
            ->first();

        if (!$played && !$stat) {
            return response()->json([
                'success' => true,
                'player_id' => $player->id,
                'played' => false,
                'stat_id' => null,
            ]);
        }

        $stat = PlayerStat::firstOrCreate(
            [
                'match_id' => $match->id,
                'player_id' => $player->id,
            ],
            [
                'points' => 0,
                'rebounds' => 0,
                'assists' => 0,
                'fouls' => 0,
                'blocks' => 0,
                'steals' => 0,
                'played' => $played,
            ]
        );

        $stat->update(['played' => $played]);

        return response()->json([
            'success' => true,
            'player_id' => $player->id,
            'played' => (bool) $stat->played,
            'stat_id' => $stat->id,
        ]);
    }

    /**
     * Update match quarter (AJAX)
     */
    public function updateQuarter(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'quarter' => 'required|in:1st,2nd,3rd,4th,OT,finished',
        ]);

        $match->update([
            'quarter' => $validated['quarter'],
        ]);

        // Auto-set status to ongoing if not already
        if ($match->status === 'upcoming') {
            $match->update(['status' => 'ongoing']);
        }

        return response()->json([
            'success' => true,
            'quarter' => $match->quarter,
            'status' => $match->status,
        ]);
    }

    /**
     * Save match and finalize
     */
    public function saveMatch(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'status' => 'required|in:upcoming,ongoing,paused,finished',
        ]);

        $match->update([
            'status' => $validated['status'],
        ]);

        // Update team records if match is finished
        if ($validated['status'] === 'finished') {
            $this->updateTeamRecords($match);
        }

        return redirect()->route('committee.matches.index')
            ->with('success', 'Match saved successfully.');
    }

    /**
     * Update team wins/losses based on match result
     */
    private function updateTeamRecords(GameMatch $match)
    {
        if ($match->team_a_score > $match->team_b_score) {
            $match->teamA->increment('wins');
            $match->teamA->increment('points', 2);
            $match->teamB->increment('losses');
        } elseif ($match->team_b_score > $match->team_a_score) {
            $match->teamB->increment('wins');
            $match->teamB->increment('points', 2);
            $match->teamA->increment('losses');
        }
    }
}
