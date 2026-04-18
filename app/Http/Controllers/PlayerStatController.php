<?php

namespace App\Http\Controllers;

use App\Models\PlayerStat;
use App\Models\Player;
use App\Models\GameMatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayerStatController extends Controller
{
    /**
     * Display all player stats for committee overview
     */
    public function committeeIndex()
    {
        $matches = GameMatch::with(['teamA', 'teamB'])
            ->withCount('playerStats')
            ->orderBy('match_date', 'desc')
            ->paginate(10);
        
        return Inertia::render('Committee/Stats/Index', [
            'matches' => $matches,
        ]);
    }

    /**
     * Display a listing of player stats for a specific match
     */
    public function index(GameMatch $match)
    {
        $stats = PlayerStat::with(['player.team', 'match'])
            ->where('match_id', $match->id)
            ->get();
        
        return Inertia::render('Committee/PlayerStats/Index', [
            'stats' => $stats,
            'match' => $match->load(['teamA', 'teamB'])
        ]);
    }

    /**
     * Show the form for creating new player stats
     */
    public function create(GameMatch $match)
    {
        $teamAPlayers = Player::where('team_id', $match->team_a_id)->get();
        $teamBPlayers = Player::where('team_id', $match->team_b_id)->get();
        
        return Inertia::render('Committee/PlayerStats/Create', [
            'match' => $match->load(['teamA', 'teamB']),
            'teamAPlayers' => $teamAPlayers,
            'teamBPlayers' => $teamBPlayers
        ]);
    }

    /**
     * Store newly created player stats
     */
    public function store(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'points' => 'required|integer|min:0',
            'assists' => 'required|integer|min:0',
            'rebounds' => 'required|integer|min:0',
            'blocks' => 'nullable|integer|min:0',
            'steals' => 'nullable|integer|min:0',
            'fouls' => 'nullable|integer|min:0|max:6',
        ]);

        PlayerStat::updateOrCreate(
            [
                'match_id' => $match->id,
                'player_id' => $validated['player_id'],
            ],
            [
                'points' => $validated['points'],
                'assists' => $validated['assists'],
                'rebounds' => $validated['rebounds'],
                'blocks' => $validated['blocks'] ?? 0,
                'steals' => $validated['steals'] ?? 0,
                'fouls' => $validated['fouls'] ?? 0,
                'played' => true,
            ]
        );

        return redirect()->route('committee.player-stats.index', $match)
            ->with('success', 'Player stats added successfully.');
    }

    /**
     * Show the form for editing player stats
     */
    public function edit(GameMatch $match, PlayerStat $playerStat)
    {
        return Inertia::render('Committee/PlayerStats/Edit', [
            'stat' => $playerStat->load(['player', 'match']),
            'match' => $match->load(['teamA', 'teamB'])
        ]);
    }

    /**
     * Update the specified player stat
     */
    public function update(Request $request, GameMatch $match, PlayerStat $playerStat)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:0',
            'assists' => 'required|integer|min:0',
            'rebounds' => 'required|integer|min:0',
            'blocks' => 'nullable|integer|min:0',
            'steals' => 'nullable|integer|min:0',
            'fouls' => 'nullable|integer|min:0|max:6',
        ]);

        $playerStat->update([
            'points' => $validated['points'],
            'assists' => $validated['assists'],
            'rebounds' => $validated['rebounds'],
            'blocks' => $validated['blocks'] ?? $playerStat->blocks,
            'steals' => $validated['steals'] ?? $playerStat->steals,
            'fouls' => $validated['fouls'] ?? $playerStat->fouls,
        ]);

        return redirect()->route('committee.player-stats.index', $match)
            ->with('success', 'Player stats updated successfully.');
    }

    /**
     * Remove the specified player stat
     */
    public function destroy(GameMatch $match, PlayerStat $playerStat)
    {
        $playerStat->delete();

        return redirect()->route('committee.player-stats.index', $match)
            ->with('success', 'Player stats deleted successfully.');
    }
}
