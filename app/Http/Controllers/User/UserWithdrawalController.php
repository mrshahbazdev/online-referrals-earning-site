<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\User;
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
        
        $cooldownDays = $user->withdrawal_days_override !== null 
            ? $user->withdrawal_days_override 
            : ($user->level->withdrawal_days ?? 7);

        $lastWithdrawal = WithdrawalRequest::where('user_id', $user->id)
            ->latest()
            ->first();
            
        $nextAvailableDate = null;
        $canWithdraw = true;
        
        if ($lastWithdrawal) {
            $nextAvailableDate = Carbon::parse($lastWithdrawal->created_at)->addDays($cooldownDays);
            if ($nextAvailableDate->isFuture()) {
                $canWithdraw = false;
            }
        }
        
        // Referral requirement check
        $referralRequirementMet = true;
        $referralsRequired = $user->referrals_required_for_withdrawal !== null 
            ? $user->referrals_required_for_withdrawal 
            : ($user->level->referrals_required_for_withdrawal ?? 1);
        
        if ($lastWithdrawal && !$user->bypass_referral_requirement && $referralsRequired > 0) {
            // Check if user has referred someone who is KYC approved AFTER the last withdrawal
            $newVerifiedReferrals = User::where('referred_by_id', $user->id)
                ->where('kyc_status', 'approved')
                ->where('created_at', '>', $lastWithdrawal->created_at)
                ->count();
                
            if ($newVerifiedReferrals < $referralsRequired) {
                $referralRequirementMet = false;
            }
        }
            
        $levelLimit = $user->withdrawal_limit_override !== null 
            ? $user->withdrawal_limit_override 
            : ($user->level->weekly_withdrawal_limit ?? 0);
        
        if ($levelLimit > 0) {
            $maxWithdrawal = $levelLimit;
        } else {
            $maxWithdrawal = $user->balance; // Treat 0 as unlimited
        }

        return view('withdrawals.create', compact('user', 'isEligible', 'lastWithdrawal', 'canWithdraw', 'nextAvailableDate', 'maxWithdrawal', 'referralRequirementMet', 'referralsRequired'));
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

        $cooldownDays = $user->withdrawal_days_override !== null 
            ? $user->withdrawal_days_override 
            : ($user->level->withdrawal_days ?? 7);

        $lastWithdrawal = WithdrawalRequest::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($lastWithdrawal) {
            $nextAvailableDate = Carbon::parse($lastWithdrawal->created_at)->addDays($cooldownDays);
            if ($nextAvailableDate->isFuture()) {
                return back()->with('error', 'You must wait ' . $cooldownDays . ' days between withdrawals.');
            }
            
            // Referral Requirement
            $referralsRequired = $user->referrals_required_for_withdrawal !== null
                ? $user->referrals_required_for_withdrawal
                : ($user->level->referrals_required_for_withdrawal ?? 1);
            
            if (!$user->bypass_referral_requirement && $referralsRequired > 0) {
                $newVerifiedReferrals = User::where('referred_by_id', $user->id)
                    ->where('kyc_status', 'approved')
                    ->where('created_at', '>', $lastWithdrawal->created_at)
                    ->count();
                    
                if ($newVerifiedReferrals < $referralsRequired) {
                    $usersText = $referralsRequired == 1 ? '1 new user' : $referralsRequired . ' new users';
                    return back()->with('error', 'You must refer at least ' . $usersText . ' (with verified KYC) after your last withdrawal to withdraw again.');
                }
            }
        }

        $levelLimit = $user->withdrawal_limit_override !== null 
            ? $user->withdrawal_limit_override 
            : ($user->level->weekly_withdrawal_limit ?? 0);
        
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
            'amount.max' => 'The withdrawal amount exceeds your available balance or withdrawal limit.'
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
