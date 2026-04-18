<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index()
    {
        $announcements = Announcement::with('author')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Check if request is from admin routes
        if (request()->routeIs('admin.*')) {
            return Inertia::render('Admin/Announcements/Index', [
                'announcements' => $announcements
            ]);
        }
        
        return Inertia::render('Committee/Announcements/Index', [
            'announcements' => $announcements
        ]);
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        // Check if request is from admin routes
        if (request()->routeIs('admin.*')) {
            return Inertia::render('Admin/Announcements/Create');
        }
        
        return Inertia::render('Committee/Announcements/Create');
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_published' => $validated['is_published'] ?? true,
            'posted_by' => auth()->id(),
        ]);

        // Redirect based on route
        if (request()->routeIs('admin.*')) {
            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement created successfully.');
        }

        return redirect()->route('committee.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        // Check if request is from admin routes
        if (request()->routeIs('admin.*')) {
            return Inertia::render('Admin/Announcements/Edit', [
                'announcement' => $announcement
            ]);
        }
        
        return Inertia::render('Committee/Announcements/Edit', [
            'announcement' => $announcement
        ]);
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $announcement->update($validated);

        // Redirect based on route
        if (request()->routeIs('admin.*')) {
            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement updated successfully.');
        }

        return redirect()->route('committee.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        // Redirect based on route
        if (request()->routeIs('admin.*')) {
            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement deleted successfully.');
        }

        return redirect()->route('committee.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
