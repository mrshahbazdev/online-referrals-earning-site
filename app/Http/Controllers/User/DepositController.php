<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DepositMethod;
use App\Models\InvestmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Show the form for creating a new deposit request.
     */
    public function create()
    {
        $user = Auth::user();
        $depositMethods = DepositMethod::where('is_active', true)->get();

        // User ki aakhri pending request haasil karein
        $pendingRequest = InvestmentRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view('deposits.create', compact('depositMethods', 'pendingRequest'));
    }

    /**
     * Store a newly created deposit request in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // **NAYA CHECK:** Request process karne se pehle check karein
        $hasPendingRequest = InvestmentRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingRequest) {
            return back()->with('error', 'You already have a pending deposit request.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:12048',
        ]);

        $path = $request->file('proof')->store('proofs', 'public');

        InvestmentRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'transaction_id_image_url' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your deposit request has been submitted and is pending approval.');
    }
}
