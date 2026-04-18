<?php

namespace App\Http\Controllers;

use App\Models\PlayoffSlot;
use App\Models\Division;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayoffController extends Controller
{
    /**
     * Display the standings/playoff bracket management page
     */
    public function index(Request $request)
    {
        $divisions = Division::with('teams')->get();
        $selectedDivisionId = $request->query('division_id');
        
        $slotsQuery = PlayoffSlot::with(['team', 'division'])
            ->orderBy('bracket_side')
            ->orderBy('round')
            ->orderBy('position');
        
        if ($selectedDivisionId) {
            $slotsQuery->where('division_id', $selectedDivisionId);
        } else {
            $slotsQuery->whereNull('division_id');
        }
        
        $playoffSlots = $slotsQuery->get();
        
        // Group slots by bracket side
        $upperBracket = $playoffSlots->where('bracket_side', 'upper')->values();
        $lowerBracket = $playoffSlots->where('bracket_side', 'lower')->values();
        
        // Get teams - filter by division if selected
        $teamsQuery = Team::with('division')->orderBy('name');
        if ($selectedDivisionId) {
            $teamsQuery->where('division_id', $selectedDivisionId);
        }
        $teams = $teamsQuery->get();
        
        return Inertia::render('Admin/Standings/Index', [
            'divisions' => $divisions,
            'upperBracket' => $upperBracket,
            'lowerBracket' => $lowerBracket,
            'teams' => $teams,
            'selectedDivisionId' => $selectedDivisionId ? (int) $selectedDivisionId : null,
        ]);
    }

    /**
     * Add a slot to a bracket side
     */
    public function addSlot(Request $request)
    {
        $validated = $request->validate([
            'bracket_side' => 'required|in:upper,lower',
            'position' => 'required|integer|min:1',
            'round' => 'required|integer|min:1',
            'division_id' => 'nullable|exists:divisions,id',
            'label' => 'nullable|string|max:255',
        ]);

        PlayoffSlot::create($validated);

        return redirect()->back()->with('success', 'Playoff slot added successfully.');
    }

    /**
     * Update a playoff slot (assign team, update label, etc.)
     */
    public function updateSlot(Request $request, PlayoffSlot $slot)
    {
        $validated = $request->validate([
            'team_id' => 'nullable|exists:teams,id',
            'label' => 'nullable|string|max:255',
            'position' => 'sometimes|integer|min:1',
            'round' => 'sometimes|integer|min:1',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $slot->update($validated);

        return redirect()->back()->with('success', 'Playoff slot updated successfully.');
    }

    /**
     * Remove a playoff slot
     */
    public function removeSlot(PlayoffSlot $slot)
    {
        $slot->delete();

        return redirect()->back()->with('success', 'Playoff slot removed successfully.');
    }

    /**
     * Generate bracket slots automatically based on number of teams
     */
    public function generateBracket(Request $request)
    {
        $validated = $request->validate([
            'bracket_side' => 'required|in:upper,lower',
            'num_teams' => 'required|integer|min:2|max:16',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $bracketSide = $validated['bracket_side'];
        $numTeams = $validated['num_teams'];
        $divisionId = $validated['division_id'] ?? null;

        // Clear existing slots for this bracket side and division
        PlayoffSlot::where('bracket_side', $bracketSide)
            ->where(function ($q) use ($divisionId) {
                if ($divisionId) {
                    $q->where('division_id', $divisionId);
                } else {
                    $q->whereNull('division_id');
                }
            })
            ->delete();

        if ($bracketSide === 'upper') {
            $this->generateUpperBracket($numTeams, $divisionId);
        } else {
            $this->generateLowerBracket($numTeams, $divisionId);
        }

        return redirect()->back()->with('success', ucfirst($bracketSide) . ' bracket generated.');
    }

    /**
     * Generate upper bracket slots
     */
    private function generateUpperBracket($numTeams, $divisionId)
    {
        // Calculate rounds needed (e.g., 8 teams = 3 rounds: 8->4->2)
        $rounds = (int) ceil(log($numTeams, 2));
        
        // Create slots for each round
        for ($round = 1; $round <= $rounds; $round++) {
            // Round 1 has numTeams slots, then halves each round
            $slotsInRound = (int) ($numTeams / pow(2, $round - 1));
            
            for ($pos = 1; $pos <= $slotsInRound; $pos++) {
                $label = null;
                
                if ($round === 1) {
                    $label = "Seed " . $pos;
                } elseif ($slotsInRound === 2) {
                    $label = "Upper Finals " . $pos;
                } elseif ($slotsInRound === 4) {
                    $label = "Upper Semi " . $pos;
                }
                
                PlayoffSlot::create([
                    'bracket_side' => 'upper',
                    'position' => $pos,
                    'round' => $round,
                    'division_id' => $divisionId,
                    'label' => $label,
                ]);
            }
        }

        // Add winner slot
        PlayoffSlot::create([
            'bracket_side' => 'upper',
            'position' => 1,
            'round' => $rounds + 1,
            'division_id' => $divisionId,
            'label' => 'Upper Bracket Winner',
        ]);
    }

    /**
     * Generate lower bracket slots for double elimination
     * 
     * For 8-team upper (4 losers):
     * - LB R1: 4 slots (UR1 losers) → 2 winners
     * - LB R2: 4 slots (2 LB R1 W + 2 UR2 losers) → 2 winners
     * - LB R3: 2 slots (internal, LB R2 winners fight) → 1 winner
     * - LB R4: 2 slots (1 LB R3 W + 1 Upper Finals loser)
     * - LB R5: 1 slot (Lower Bracket Winner)
     * 
     * For 16-team upper (8 losers):
     * - LB R1: 8 slots (UR1 losers) → 4 winners
     * - LB R2: 8 slots (4 LB R1 W + 4 UR2 losers) → 4 winners
     * - LB R3: 4 slots (internal) → 2 winners
     * - LB R4: 2 slots (internal) → 1 winner
     * - LB R5: 2 slots (1 LB R4 W + 1 Upper Finals loser)
     * - LB R6: 1 slot (Lower Bracket Winner)
     */
    private function generateLowerBracket($numTeams, $divisionId)
    {
        // numTeams = losers from Upper R1 (e.g., 4 for 8-team upper, 8 for 16-team upper)
        $lbRound = 1;
        
        // Calculate upper bracket structure
        $upperTeams = $numTeams * 2;
        $upperRounds = (int) ceil(log($upperTeams, 2));
        
        // LB Round 1: All losers from Upper R1 fight each other
        for ($pos = 1; $pos <= $numTeams; $pos++) {
            PlayoffSlot::create([
                'bracket_side' => 'lower',
                'position' => $pos,
                'round' => $lbRound,
                'division_id' => $divisionId,
                'label' => "Loser UR1-" . $pos,
            ]);
        }
        $lbRound++;
        $currentTeams = (int) ($numTeams / 2); // Winners from LB R1
        
        // LB Round 2: Mix LB R1 winners with UR2 losers
        if ($upperRounds >= 2) {
            $losersFromUR2 = (int) ($upperTeams / pow(2, 2));
            $totalSlots = $currentTeams + $losersFromUR2;
            
            for ($pos = 1; $pos <= $totalSlots; $pos++) {
                if ($pos <= $currentTeams) {
                    $label = "LB R" . ($lbRound - 1) . " W" . $pos;
                } else {
                    $label = "Loser UR2-" . ($pos - $currentTeams);
                }
                
                PlayoffSlot::create([
                    'bracket_side' => 'lower',
                    'position' => $pos,
                    'round' => $lbRound,
                    'division_id' => $divisionId,
                    'label' => $label,
                ]);
            }
            $currentTeams = (int) ($totalSlots / 2);
            $lbRound++;
        }
        
        // Internal rounds: LB winners fight among themselves until 1 remains
        while ($currentTeams >= 2) {
            for ($pos = 1; $pos <= $currentTeams; $pos++) {
                PlayoffSlot::create([
                    'bracket_side' => 'lower',
                    'position' => $pos,
                    'round' => $lbRound,
                    'division_id' => $divisionId,
                    'label' => "LB R" . ($lbRound - 1) . " W" . $pos,
                ]);
            }
            $currentTeams = (int) ($currentTeams / 2);
            $lbRound++;
        }
        
        // Final round: LB Champion vs Upper Finals loser (only if upper has 3+ rounds)
        if ($upperRounds > 2) {
            PlayoffSlot::create([
                'bracket_side' => 'lower',
                'position' => 1,
                'round' => $lbRound,
                'division_id' => $divisionId,
                'label' => "LB R" . ($lbRound - 1) . " W1",
            ]);
            PlayoffSlot::create([
                'bracket_side' => 'lower',
                'position' => 2,
                'round' => $lbRound,
                'division_id' => $divisionId,
                'label' => "Loser Upper Finals",
            ]);
            $lbRound++;
        }
        
        // Add LB Winner slot
        PlayoffSlot::create([
            'bracket_side' => 'lower',
            'position' => 1,
            'round' => $lbRound,
            'division_id' => $divisionId,
            'label' => 'Lower Bracket Winner',
        ]);
    }

    /**
     * Clear all slots from a bracket side
     */
    public function clearBracket(Request $request)
    {
        $validated = $request->validate([
            'bracket_side' => 'required|in:upper,lower',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $query = PlayoffSlot::where('bracket_side', $validated['bracket_side']);
        
        if (isset($validated['division_id'])) {
            $query->where('division_id', $validated['division_id']);
        }
        
        $query->delete();

        return redirect()->back()->with('success', ucfirst($validated['bracket_side']) . ' bracket cleared successfully.');
    }
}
