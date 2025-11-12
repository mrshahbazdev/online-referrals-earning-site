<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logTypes = ['User Management', 'Authentication']; // Define available log types
        $selectedType = $request->query('type', $logTypes[0]); // Get type from URL or default

        $logs = AdminActivityLog::with('admin')
            ->where('log_type', $selectedType)
            ->latest()
            ->simplePaginate(15);

        return view('admin.logs.index', compact('logs', 'logTypes', 'selectedType'));
    }
}
