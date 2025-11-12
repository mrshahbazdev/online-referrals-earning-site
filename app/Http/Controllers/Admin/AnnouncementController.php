<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement = Announcement::create($request->all());

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Announcements',
            'action' => 'Announcement Created',
            'description' => 'Admin created a new announcement: ' . $announcement->title,
        ]);

        return back()->with('success', 'Announcement created successfully.');
    }
    public function toggleStatus(Announcement $announcement)
    {
        // Status ko tabdeel karein (true se false, false se true)
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $status = $announcement->is_active ? 'Activated' : 'Deactivated';

        // Activity log karein
        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Announcements',
            'action' => 'Status Changed',
            'description' => "Admin {$status} announcement: " . $announcement->title,
        ]);

        return back()->with('success', 'Announcement status updated successfully.');
    }
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement->update($request->all());

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Announcements',
            'action' => 'Announcement Updated',
            'description' => 'Admin updated announcement: ' . $announcement->title,
        ]);

        return back()->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcementTitle = $announcement->title;
        $announcement->delete();

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Announcements',
            'action' => 'Announcement Deleted',
            'description' => 'Admin deleted announcement: ' . $announcementTitle,
        ]);

        return back()->with('success', 'Announcement deleted successfully.');
    }
}
