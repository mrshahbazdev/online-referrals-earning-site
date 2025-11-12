<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function index()
    {
        $logs = UserActivityLog::with('user')->latest()->simplePaginate(15);
        return view('admin.user_logs.index', compact('logs'));
    }
}
