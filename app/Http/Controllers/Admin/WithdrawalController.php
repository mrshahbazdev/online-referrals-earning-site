<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class WithdrawalController extends Controller
{
    public function index()
    {
        $requests = WithdrawalRequest::with('user')->latest()->paginate(10);
        return view('admin.withdrawals.index', compact('requests'));
    }

    public function update(Request $request, \App\Models\WithdrawalRequest $withdrawalRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'reject_reason' => 'nullable|string|max:1000',
        ]);
    
        if ($withdrawalRequest->status !== 'pending') {
            return back()->withErrors('This request has already been processed.');
        }
    
        $user = $withdrawalRequest->user;
        $newStatus = $request->status;
        $rejectReason = $request->reject_reason;
    
        // **Case 1: Agar request APPROVE ho jaye**
        if ($newStatus == 'approved') {
            // Check karein ke user ke paas abhi bhi kaafi balance hai
            if ($user->balance < $withdrawalRequest->amount) {
                $withdrawalRequest->update(['status' => 'rejected']); // Request ko reject kar dein
                return back()->withErrors('User has insufficient balance. Request has been rejected.');
            }
    
            // Ab balance se raqam kaat lein
            $user->balance -= $withdrawalRequest->amount;
            $user->save();
    
            // Ab transaction record banayein
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'amount' => -$withdrawalRequest->amount,
                'type' => 'withdrawal',
                'description' => 'Withdrawal approved by admin.',
            ]);
        }
    
        // Request ka status update karein (chahe approve ho ya reject)
        $withdrawalRequest->update([
            'status' => $newStatus,
            'reject_reason' => $newStatus == 'rejected' ? $rejectReason : null,
        ]);
    
        // Admin ki activity log karein
        \App\Models\AdminActivityLog::create([
            'admin_id' => \Illuminate\Support\Facades\Auth::id(),
            'log_type' => 'Withdrawal Management',
            'action' => 'Withdrawal Status Updated',
            'description' => 'Admin ' . $newStatus . ' withdrawal of $' . $withdrawalRequest->amount . ' for user: ' . $user->username,
        ]);
    
        return back()->with('success', 'Withdrawal status updated successfully.');
    }
}
