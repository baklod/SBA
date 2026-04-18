<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SettingController extends Controller
{
    /**
     * Display the settings dashboard.
     */
    public function index()
    {
        $offSeason = Setting::where('key', 'off_season')->value('value') === 'true';
        $registrationOpen = Setting::where('key', 'registration_open')->value('value') !== 'false'; // default true if not set
        $registrationClosingDate = Setting::where('key', 'registration_closing_date')->value('value');
        $teamRegistrationOpen = Setting::where('key', 'team_registration_open')->value('value') !== 'false'; // default true if not set
        $teamRegistrationClosingDate = Setting::where('key', 'team_registration_closing_date')->value('value');

        return Inertia::render('Admin/Settings', [
            'offSeason' => $offSeason,
            'registrationOpen' => $registrationOpen,
            'registrationClosingDate' => $registrationClosingDate,
            'teamRegistrationOpen' => $teamRegistrationOpen,
            'teamRegistrationClosingDate' => $teamRegistrationClosingDate,
        ]);
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Update off-season status.
     */
    public function updateOffSeason(Request $request)
    {
        $request->validate([
            'off_season' => ['required', 'boolean'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'off_season'],
            ['value' => $request->off_season ? 'true' : 'false']
        );

        return back()->with('success', 'Off-season setting updated successfully.');
    }

    /**
     * Update Player Registration Settings
     */
    public function updateRegistration(Request $request)
    {
        $request->validate([
            'registration_open' => ['required', 'boolean'],
            'registration_closing_date' => ['nullable', 'date'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'registration_open'],
            ['value' => $request->registration_open ? 'true' : 'false']
        );

        Setting::updateOrCreate(
            ['key' => 'registration_closing_date'],
            ['value' => $request->registration_closing_date]
        );

        return back()->with('success', 'Registration settings updated successfully.');
    }

    /**
     * Update Team Registration Settings
     */
    public function updateTeamRegistration(Request $request)
    {
        $request->validate([
            'team_registration_open' => ['required', 'boolean'],
            'team_registration_closing_date' => ['nullable', 'date'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'team_registration_open'],
            ['value' => $request->team_registration_open ? 'true' : 'false']
        );

        Setting::updateOrCreate(
            ['key' => 'team_registration_closing_date'],
            ['value' => $request->team_registration_closing_date]
        );

        return back()->with('success', 'Team registration setting updated successfully.');
    }

    /**
     * Wipe all non-account data.
     */
    public function wipeData(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        try {
            Schema::disableForeignKeyConstraints();

            // TRUNCATE is not reliably transactional on all DB engines (e.g. MySQL),
            // so avoid wrapping this operation in DB::transaction().
            \App\Models\PlayoffSlot::truncate();
            \App\Models\PlayerStat::truncate();
            \App\Models\GameMatch::truncate();
            \App\Models\Player::truncate();
            \App\Models\Team::truncate();
            \App\Models\Division::truncate();
            \App\Models\Announcement::truncate();

            return back()->with('success', 'All season data has been wiped successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'An error occurred while wiping data: ' . $e->getMessage());
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}
