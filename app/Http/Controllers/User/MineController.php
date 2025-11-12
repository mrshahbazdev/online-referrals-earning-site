<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Carbon\Carbon;

class MineController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('level');

        // Main Wallet (Total Balance)
        $mainWallet = $user->balance;

        // Commission Wallet (Sirf commission ki earnings)
        $commissionWallet = Transaction::where('user_id', $user->id)
            ->where('type', 'commission')
            ->sum('amount');

        // Total Deposit (Sirf approved investments)
        $totalDeposit = Transaction::where('user_id', $user->id)
            ->where('type', 'investment')
            ->sum('amount');

        // Total Withdraw (Sirf approved withdrawals - amount negative mein hota hai)
        $totalWithdraw = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->sum('amount');

        // Today's Profit (Task rewards + commissions aaj ke din)
        $todayProfit = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['task_reward', 'commission'])
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        // Total Profit (Tamam task rewards + commissions)
        $totalProfit = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['task_reward', 'commission'])
            ->sum('amount');

        // Nayi line: Level 1 ka joining bonus config se haasil karein
        $joiningBonus = config('referrals.joining_bonus_levels.1', 0);

        return view('mine.index', compact(
            'user',
            'mainWallet',
            'commissionWallet',
            'totalDeposit',
            'totalWithdraw',
            'todayProfit',
            'totalProfit',
            'joiningBonus' // Naye variable ko view mein bhejein
        ));
    }
}
