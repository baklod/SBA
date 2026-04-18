<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use App\Models\GameMatch;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function adminDashboard()
    {
        $stats = [
            'total_teams' => Team::count(),
            'total_players' => Player::count(),
            'total_matches' => GameMatch::count(),
            'upcoming_matches' => GameMatch::where('status', 'upcoming')->count(),
            'ongoing_matches' => GameMatch::where('status', 'ongoing')->count(),
            'finished_matches' => GameMatch::where('status', 'finished')->count(),
        ];

        $recentMatches = GameMatch::with(['teamA', 'teamB'])
            ->orderBy('match_date', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentMatches' => $recentMatches,
        ]);
    }

    /**
     * Display the committee dashboard
     */
    public function committeeDashboard()
    {
        $stats = [
            'assigned_matches' => GameMatch::count(),
            'ongoing_matches' => GameMatch::where('status', 'ongoing')->count(),
            'pending_stats' => GameMatch::where('status', 'finished')
                ->whereDoesntHave('playerStats')
                ->count(),
        ];

        $upcomingMatches = GameMatch::with(['teamA', 'teamB'])
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('match_date', 'asc')
            ->limit(5)
            ->get();

        return Inertia::render('Committee/Dashboard', [
            'stats' => $stats,
            'upcomingMatches' => $upcomingMatches,
        ]);
    }
}
