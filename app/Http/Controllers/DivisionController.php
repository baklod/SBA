<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DivisionController extends Controller
{
    /**
     * Display a listing of divisions
     */
    public function index()
    {
        $divisions = Division::withCount('teams')->get();
        
        return Inertia::render('Admin/Divisions/Index', [
            'divisions' => $divisions
        ]);
    }

    /**
     * Show the form for creating a new division
     */
    public function create()
    {
        return Inertia::render('Admin/Divisions/Create');
    }

    /**
     * Store a newly created division
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name',
            'description' => 'nullable|string|max:500',
        ]);

        Division::create($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division created successfully.');
    }

    /**
     * Show the form for editing the specified division
     */
    public function edit(Division $division)
    {
        return Inertia::render('Admin/Divisions/Edit', [
            'division' => $division
        ]);
    }

    /**
     * Update the specified division
     */
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string|max:500',
        ]);

        $division->update($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division updated successfully.');
    }

    /**
     * Remove the specified division
     */
    public function destroy(Division $division)
    {
        // Check if division has teams
        if ($division->teams()->count() > 0) {
            return redirect()->route('admin.divisions.index')
                ->with('error', 'Cannot delete division with existing teams.');
        }

        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Division deleted successfully.');
    }
}
