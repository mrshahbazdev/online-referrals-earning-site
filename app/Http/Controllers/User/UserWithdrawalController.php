<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserWithdrawalController extends Controller
{
    /**
     * Show the form for creating a new withdrawal request.
     */
    public function create()
    {
        $user = Auth::user()->load('level');
        
        // Yaqeen karein ke user ka level set hai
        if (!$user->level) {
            return redirect()->route('home')->with('error', 'Your account level is not configured. Please contact support.');
        }

        $isEligible = $user->isEligibleForWithdrawal();
        $endOfWeek = Carbon::now()->endOfWeek();

        $lastWithdrawal = WithdrawalRequest::where('user_id', $user->id)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), $endOfWeek])
            ->first();
            
        $levelLimit = $user->level->weekly_withdrawal_limit ?? 0;
        
        if ($levelLimit > 0) {
            $maxWithdrawal = $levelLimit;
        } else {
            $maxWithdrawal = $user->balance; // Treat 0 as unlimited
        }

        return view('withdrawals.create', compact('user', 'isEligible', 'lastWithdrawal', 'endOfWeek', 'maxWithdrawal'));
    }

    /**
     * Store a newly created withdrawal request in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEligibleForWithdrawal()) {
            return back()->with('error', 'Please complete your profile and KYC to enable withdrawals.');
        }

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $hasWithdrawnThisWeek = WithdrawalRequest::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->exists();

        if ($hasWithdrawnThisWeek) {
            return back()->with('error', 'You can only make one withdrawal request per week.');
        }

        $levelLimit = $user->level->weekly_withdrawal_limit ?? 0;
        
        if ($levelLimit > 0) {
            $maxAllowed = min($levelLimit, $user->balance);
        } else {
            $maxAllowed = $user->balance; // Treat 0 as unlimited
        }

        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $maxAllowed,
            'method' => 'required|string|max:50',
            'account_title' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
        ], [
            'amount.max' => 'The withdrawal amount exceeds your available balance or weekly limit.'
        ]);
        
        // Sirf request banayein. Balance se paisay na kaatein.
        WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'method' => $request->method,
            'account_title' => $request->account_title,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your withdrawal request has been submitted and is pending approval.');
    }
}
