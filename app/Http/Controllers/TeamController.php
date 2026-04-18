<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Team;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    private function storeTeamLogoInPublic(UploadedFile $logo): string
    {
        $destination = public_path('uploads/teams');

        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = $logo->hashName();
        $logo->move($destination, $filename);

        return "/uploads/teams/{$filename}";
    }

    private function deleteTeamLogo(?string $logoPath): void
    {
        if (!$logoPath) {
            return;
        }

        $relativePublicPath = null;
        if (str_starts_with($logoPath, '/uploads/teams/')) {
            $relativePublicPath = ltrim($logoPath, '/');
        } elseif (str_starts_with($logoPath, 'uploads/teams/')) {
            $relativePublicPath = $logoPath;
        }

        if ($relativePublicPath) {
            $fullPublicPath = public_path($relativePublicPath);
            if (File::exists($fullPublicPath)) {
                File::delete($fullPublicPath);
                return;
            }
        }

        // Legacy fallback: storage/app/public/... (served via /storage if storage:link exists)
        if (str_starts_with($logoPath, '/storage/')) {
            $logoPath = ltrim(str_replace('/storage/', '', $logoPath), '/');
        }
        Storage::disk('public')->delete($logoPath);
    }

    /**
     * Display a listing of teams
     */
    public function index()
    {
        $teams = Team::with('division')->withCount('players')->get();
        
        return Inertia::render('Admin/Teams/Index', [
            'teams' => $teams
        ]);
    }

    /**
     * Show the form for creating a new team
     */
    public function create()
    {
        $divisions = Division::all();
        
        return Inertia::render('Admin/Teams/Create', [
            'divisions' => $divisions
        ]);
    }

    /**
     * Store a newly created team
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->storeTeamLogoInPublic($request->file('logo'));
        }

        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team created successfully.');
    }

    /**
     * Show the form for editing the specified team
     */
    public function edit(Team $team)
    {
        $divisions = Division::all();
        
        return Inertia::render('Admin/Teams/Edit', [
            'team' => $team->load('division'),
            'divisions' => $divisions
        ]);
    }

    /**
     * Update the specified team
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_logo' => 'nullable|boolean',
            'wins' => 'nullable|integer|min:0',
            'losses' => 'nullable|integer|min:0',
            'points' => 'nullable|integer|min:0',
        ]);

        // Handle logo removal
        if ($request->boolean('remove_logo') && $team->logo) {
            $this->deleteTeamLogo($team->logo);
            $validated['logo'] = null;
        }
        // Handle new logo upload
        elseif ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($team->logo) {
                $this->deleteTeamLogo($team->logo);
            }
            $validated['logo'] = $this->storeTeamLogoInPublic($request->file('logo'));
        }

        // Remove remove_logo from validated data
        unset($validated['remove_logo']);

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified team
     */
    public function destroy(Team $team)
    {
        // Delete logo if exists
        if ($team->logo) {
            $this->deleteTeamLogo($team->logo);
        }
        
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Team deleted successfully.');
    }
}
