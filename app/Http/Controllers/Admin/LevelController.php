<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::latest()->paginate(10);
        return view('admin.levels.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:levels',
            'upgrade_cost' => 'required|numeric|min:0',
            'daily_task_limit' => 'required|integer|min:0',
            'weekly_withdrawal_limit' => 'required|numeric|min:0',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('level_icons', 'public');
            $data['icon_url'] = $path;
        }

        $level = Level::create($data);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Level Management',
            'action' => 'Level Created',
            'description' => 'Admin created a new level: ' . $level->name,
        ]);

        return back()->with('success', 'Level created successfully.');
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:levels,name,' . $level->id,
            'upgrade_cost' => 'required|numeric|min:0',
            'daily_task_limit' => 'required|integer|min:0',
            'weekly_withdrawal_limit' => 'required|numeric|min:0',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('icon')) {
            if ($level->icon_url) {
                Storage::disk('public')->delete($level->icon_url);
            }
            $path = $request->file('icon')->store('level_icons', 'public');
            $data['icon_url'] = $path;
        }

        $level->update($data);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Level Management',
            'action' => 'Level Updated',
            'description' => 'Admin updated level: ' . $level->name,
        ]);

        return back()->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
        $levelName = $level->name;

        // Delete the icon file from storage
        if ($level->icon_url) {
            Storage::disk('public')->delete($level->icon_url);
        }

        $level->delete();

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Level Management',
            'action' => 'Level Deleted',
            'description' => 'Admin deleted level: ' . $levelName,
        ]);

        return back()->with('success', 'Level deleted successfully.');
    }
}
