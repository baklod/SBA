<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlayerController extends Controller
{
    private function storePlayerPhotoInPublic(UploadedFile $photo): string
    {
        $destination = public_path('uploads/players');

        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = $photo->hashName();
        $photo->move($destination, $filename);

        return "/uploads/players/{$filename}";
    }

    private function deletePlayerPhoto(?string $photoPath): void
    {
        if (!$photoPath) {
            return;
        }

        $relativePublicPath = null;
        if (str_starts_with($photoPath, '/uploads/players/')) {
            $relativePublicPath = ltrim($photoPath, '/');
        } elseif (str_starts_with($photoPath, 'uploads/players/')) {
            $relativePublicPath = $photoPath;
        }

        if ($relativePublicPath) {
            $fullPublicPath = public_path($relativePublicPath);
            if (File::exists($fullPublicPath)) {
                File::delete($fullPublicPath);
                return;
            }
        }

        // Legacy fallback: storage/app/public/... (served via /storage if storage:link exists)
        Storage::disk('public')->delete($photoPath);
    }

    /**
     * Display a listing of players
     */
    public function index()
    {
        $players = Player::with(['team.division'])->get();
        $teams = Team::with('division')->get();
        $divisions = Division::all();
        
        return Inertia::render('Admin/Players/Index', [
            'players' => $players,
            'teams' => $teams,
            'divisions' => $divisions
        ]);
    }

    /**
     * Export players by team as a PDF.
     */
    public function exportPdf(Request $request)
    {
        $validated = $request->validate([
            'team_id' => ['required', 'integer', 'exists:teams,id'],
        ]);

        $team = Team::with('division')->findOrFail($validated['team_id']);

        $players = Player::query()
            ->where('team_id', $team->id)
            ->orderBy('name')
            ->get(['team_id', 'name', 'jersey_number', 'position']);

        $viewData = [
            'team' => $team,
            'players' => $players,
            'generatedAt' => now(),
        ];

        // If the PDF package is unavailable for some reason, fall back to plain HTML.
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return view('admin.players.pdf', $viewData);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.players.pdf', $viewData)
            ->setPaper('a4', 'portrait');

        $safeTeamName = preg_replace('/[^A-Za-z0-9_-]+/', '_', (string) $team->name);
        $filename = 'players_' . $safeTeamName . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show the form for creating a new player
     */
    public function create()
    {
        $teams = Team::with('division')->get();
        $divisions = Division::all();
        
        return Inertia::render('Admin/Players/Create', [
            'teams' => $teams,
            'divisions' => $divisions
        ]);
    }

    /**
     * Store a newly created player
     */
    public function store(Request $request)
    {
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'jersey_number.unique' => 'This jersey number is already taken in the selected team.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->storePlayerPhotoInPublic($request->file('photo'));
        }

        Player::create($validated);

        return redirect()->route('admin.players.index')
            ->with('success', 'Player created successfully.');
    }

    /**
     * Show the form for editing the specified player
     */
    public function edit(Player $player)
    {
        $teams = Team::with('division')->get();
        $divisions = Division::all();
        
        return Inertia::render('Admin/Players/Edit', [
            'player' => $player->load('team.division'),
            'teams' => $teams,
            'divisions' => $divisions
        ]);
    }

    /**
     * Update the specified player
     */
    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'jersey_number' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('players')->where('team_id', $request->team_id)->ignore($player->id)
            ],
            'position' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_photo' => 'nullable|boolean',
        ], [
            'jersey_number.unique' => 'This jersey number is already taken in the selected team.',
        ]);

        // Handle photo removal
        if ($request->boolean('remove_photo') && $player->photo) {
            $this->deletePlayerPhoto($player->photo);
            $validated['photo'] = null;
        }
        // Handle new photo upload
        elseif ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($player->photo) {
                $this->deletePlayerPhoto($player->photo);
            }
            $validated['photo'] = $this->storePlayerPhotoInPublic($request->file('photo'));
        }

        // Remove remove_photo from validated data
        unset($validated['remove_photo']);

        $player->update($validated);

        return redirect()->route('admin.players.index')
            ->with('success', 'Player updated successfully.');
    }

    /**
     * Remove the specified player
     */
    public function destroy(Player $player)
    {
        if ($player->photo) {
            $this->deletePlayerPhoto($player->photo);
        }

        $player->delete();

        return redirect()->route('admin.players.index')
            ->with('success', 'Player deleted successfully.');
    }
}
