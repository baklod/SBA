<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PlayerStatController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlayoffController;
use App\Http\Controllers\TeamRegistrationRequestController;
use Illuminate\Support\Facades\Route;

// Public Routes (No Authentication Required)
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/schedule', [PublicController::class, 'schedule'])->name('schedule');
Route::get('/rankings', [PublicController::class, 'rankings'])->name('rankings');
Route::get('/team-stats', [PublicController::class, 'teamStats'])->name('team-stats');
Route::get('/individual-stats', [PublicController::class, 'individualStats'])->name('individual-stats');
Route::get('/player-stats/{player}', [PublicController::class, 'playerStats'])->name('player-stats');
Route::get('/highest-points', [PublicController::class, 'highestPoints'])->name('highest-points');
Route::get('/events', [PublicController::class, 'events'])->name('events');
Route::get('/announcements', [PublicController::class, 'announcements'])->name('announcements');
Route::get('/match/{match}', [PublicController::class, 'matchDetails'])->name('match.details');
Route::get('/standings', [PublicController::class, 'standings'])->name('standings');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/register', [PublicController::class, 'playerRegistrationForm'])->name('register');
Route::post('/register', [PublicController::class, 'submitPlayerRegistration']);
Route::get('/team-register', [PublicController::class, 'teamRegistrationForm'])->name('team-register');
Route::post('/team-register', [PublicController::class, 'submitTeamRegistration']);

// Admin Routes (Requires Authentication + Admin Role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Division Management
    Route::resource('divisions', DivisionController::class);
    
    // Team Management
    Route::resource('teams', TeamController::class);

    // Team Registration Requests
    Route::get('/team-requests', [TeamRegistrationRequestController::class, 'index'])->name('team-requests.index');
    Route::post('/team-requests/{teamRegistrationRequest}/approve', [TeamRegistrationRequestController::class, 'approve'])->name('team-requests.approve');
    Route::post('/team-requests/{teamRegistrationRequest}/reject', [TeamRegistrationRequestController::class, 'reject'])->name('team-requests.reject');
    
    // Player Management
    Route::get('players/export-pdf', [PlayerController::class, 'exportPdf'])->name('players.export-pdf');
    Route::resource('players', PlayerController::class);
    
    // Match Management
    Route::resource('matches', MatchController::class);
    
    // Announcement Management (Admin can manage all)
    Route::resource('announcements', AnnouncementController::class);

    // Event Management (Admin only)
    Route::resource('events', EventController::class);
    
    // Standings/Playoff Management
    Route::get('/standings', [PlayoffController::class, 'index'])->name('standings.index');
    Route::post('/standings/add-slot', [PlayoffController::class, 'addSlot'])->name('standings.add-slot');
    Route::put('/standings/slot/{slot}', [PlayoffController::class, 'updateSlot'])->name('standings.update-slot');
    Route::delete('/standings/slot/{slot}', [PlayoffController::class, 'removeSlot'])->name('standings.remove-slot');
    Route::post('/standings/generate-bracket', [PlayoffController::class, 'generateBracket'])->name('standings.generate-bracket');
    Route::post('/standings/clear-bracket', [PlayoffController::class, 'clearBracket'])->name('standings.clear-bracket');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/password', [\App\Http\Controllers\SettingController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/off-season', [\App\Http\Controllers\SettingController::class, 'updateOffSeason'])->name('settings.off-season');
    Route::put('/settings/registration', [\App\Http\Controllers\SettingController::class, 'updateRegistration'])->name('settings.registration');
    Route::put('/settings/team-registration', [\App\Http\Controllers\SettingController::class, 'updateTeamRegistration'])->name('settings.team-registration');
    Route::post('/settings/wipe-data', [\App\Http\Controllers\SettingController::class, 'wipeData'])->name('settings.wipe-data');
});

// Committee Routes (Requires Authentication + Committee Role)
Route::middleware(['auth', 'role:committee'])->prefix('committee')->name('committee.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'committeeDashboard'])->name('dashboard');
    
    // Match Results
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    Route::get('/matches/{match}', [MatchController::class, 'show'])->name('matches.show');
    Route::get('/matches/{match}/edit', [MatchController::class, 'edit'])->name('matches.edit');
    Route::put('/matches/{match}', [MatchController::class, 'update'])->name('matches.update');
    Route::get('/matches/{match}/process', [MatchController::class, 'process'])->name('matches.process');
    Route::post('/matches/{match}/add-score', [MatchController::class, 'addScore'])->name('matches.add-score');
    Route::post('/matches/{match}/add-foul', [MatchController::class, 'addFoul'])->name('matches.add-foul');
    Route::post('/matches/{match}/add-stat', [MatchController::class, 'addStat'])->name('matches.add-stat');
    Route::post('/matches/{match}/set-player-played', [MatchController::class, 'setPlayerPlayed'])->name('matches.set-player-played');
    Route::post('/matches/{match}/update-quarter', [MatchController::class, 'updateQuarter'])->name('matches.update-quarter');
    Route::post('/matches/{match}/save', [MatchController::class, 'saveMatch'])->name('matches.save');
    
    // Player Stats Management
    Route::get('/stats', [PlayerStatController::class, 'committeeIndex'])->name('stats.index');
    Route::get('/matches/{match}/player-stats', [PlayerStatController::class, 'index'])->name('player-stats.index');
    Route::get('/matches/{match}/player-stats/create', [PlayerStatController::class, 'create'])->name('player-stats.create');
    Route::post('/matches/{match}/player-stats', [PlayerStatController::class, 'store'])->name('player-stats.store');
    Route::get('/matches/{match}/player-stats/{playerStat}/edit', [PlayerStatController::class, 'edit'])->name('player-stats.edit');
    Route::put('/matches/{match}/player-stats/{playerStat}', [PlayerStatController::class, 'update'])->name('player-stats.update');
    Route::delete('/matches/{match}/player-stats/{playerStat}', [PlayerStatController::class, 'destroy'])->name('player-stats.destroy');
    
    // Announcement Management (Committee can manage their own)
    Route::resource('announcements', AnnouncementController::class);
});

require __DIR__.'/auth.php';

