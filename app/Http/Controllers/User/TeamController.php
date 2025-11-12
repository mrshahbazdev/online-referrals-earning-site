<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load direct referrals (Level 1)
        $level1 = $user->referrals()->get();

        // Load second-level referrals (Level 2)
        $level2 = collect();
        foreach ($level1 as $ref) {
            $level2 = $level2->merge($ref->referrals()->get());
        }

        // Load third-level referrals (Level 3)
        $level3 = collect();
        foreach ($level2 as $ref) {
            $level3 = $level3->merge($ref->referrals()->get());
        }

        // Calculate total team size
        $totalTeam = $level1->count() + $level2->count() + $level3->count();

        // Calculate total referral earnings
        $totalEarn = Transaction::where('user_id', $user->id)
            ->where('type', 'commission')
            ->sum('amount');

        return view('team.index', compact('user', 'level1', 'level2', 'level3', 'totalTeam', 'totalEarn'));
    }
}
