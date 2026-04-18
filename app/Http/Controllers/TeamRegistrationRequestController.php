<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamRegistrationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamRegistrationRequestController extends Controller
{
    /**
     * Display pending team registration requests.
     */
    public function index()
    {
        $requests = TeamRegistrationRequest::with('division')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/TeamRequests/Index', [
            'requests' => $requests,
        ]);
    }

    /**
     * Approve a team registration request.
     */
    public function approve(TeamRegistrationRequest $teamRegistrationRequest)
    {
        if ($teamRegistrationRequest->status !== 'pending') {
            return back()->with('error', 'This team request has already been reviewed.');
        }

        $team = Team::create([
            'name' => $teamRegistrationRequest->name,
            'division_id' => $teamRegistrationRequest->division_id,
            'logo' => $teamRegistrationRequest->logo,
            'wins' => 0,
            'losses' => 0,
            'points' => 0,
        ]);

        $teamRegistrationRequest->update([
            'status' => 'approved',
            'team_id' => $team->id,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Team request approved and team created.');
    }

    /**
     * Reject a team registration request.
     */
    public function reject(TeamRegistrationRequest $teamRegistrationRequest)
    {
        if ($teamRegistrationRequest->status !== 'pending') {
            return back()->with('error', 'This team request has already been reviewed.');
        }

        $teamRegistrationRequest->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Team request rejected.');
    }
}
