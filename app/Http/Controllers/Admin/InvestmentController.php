<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class InvestmentController extends Controller
{
    public function index()
    {
        $requests = InvestmentRequest::with('user')->latest()->paginate(10);
        return view('admin.investments.index', compact('requests'));
    }

    public function update(Request $request, \App\Models\InvestmentRequest $investmentRequest)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        if ($investmentRequest->status !== 'pending') {
            return back()->withErrors('This request has already been processed.');
        }

        $investmentRequest->update(['status' => $request->status]);
        $user = $investmentRequest->user;

        if ($request->status == 'approved' && $user) {
            // Check karein ke kya yeh user ka pehla deposit hai
            $isFirstDeposit = !$user->has_deposited;

            // User ka balance update karein
            $user->balance += $investmentRequest->amount;
            $user->save();

            // Transaction record banayein
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'amount' => $investmentRequest->amount,
                'type' => 'investment',
                'description' => 'Investment approved by admin.',
            ]);

            // **AGAR YEH PEHLA DEPOSIT HAI, TO JOINING BONUS DEIN**
            if ($isFirstDeposit) {
                $user->has_deposited = true;
                $user->save();

                // Referrers ko joining bonus dein
                $bonusLevels = config('referrals.joining_bonus_levels', []);
                $referrer = $user->referrer;

                for ($level = 1; $level <= count($bonusLevels); $level++) {
                    if (!$referrer) break;

                    $bonusAmount = $bonusLevels[$level] ?? 0;
                    if ($bonusAmount > 0) {
                        $referrer->balance += $bonusAmount;
                        $referrer->save();
                        \App\Models\Transaction::create([
                            'user_id' => $referrer->id,
                            'amount' => $bonusAmount,
                            'type' => 'commission',
                            'description' => "Level {$level} joining bonus for referring user: " . $user->username,
                        ]);
                    }
                    $referrer = $referrer->referrer;
                }
            }
        }

        // ... (baqi admin activity log ka code waisa hi rahega)

        return back()->with('success', 'Investment status updated successfully.');
    }
}
