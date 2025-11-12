<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Transaction;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLevelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $levels = Level::orderBy('id', 'asc')->get();

        return view('levels.index', compact('user', 'levels'));
    }

    /**
     * Handle the level upgrade request.
     */
    public function upgrade(Request $request, Level $level)
    {
        $user = Auth::user();

        // Check karein ke user pehle se hi is level par ya is se upar to nahi hai
        if ($user->level_id >= $level->id) {
            return back()->with('error', 'You are already at this level or higher.');
        }

        // Check karein ke user ke paas kaafi balance hai
        if ($user->balance < $level->upgrade_cost) {
            return back()->with('error', 'Insufficient balance. Please deposit more funds to upgrade.');
        }

        // Balance se raqam kaat lein aur level update karein
        $user->balance -= $level->upgrade_cost;
        $user->level_id = $level->id;
        $user->save();

        // Transaction record banayein
        Transaction::create([
            'user_id' => $user->id,
            'amount' => -$level->upgrade_cost,
            'type' => 'level_upgrade',
            'description' => 'Upgraded to ' . $level->name . ' level.',
        ]);

        // User activity log karein
        UserActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Level Upgraded',
            'description' => 'User upgraded to ' . $level->name,
        ]);

        return back()->with('success', 'Congratulations! You have successfully upgraded to the ' . $level->name . ' level.');
    }
}
