<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserKycController extends Controller
{
    /**
     * Show the form for creating a new KYC submission.
     */
    public function create()
    {
        $user = Auth::user();
        // User ki aakhri submission haasil karein taake status dikha sakein
        $lastSubmission = KycSubmission::where('user_id', $user->id)->latest()->first();

        return view('kyc.create', compact('user', 'lastSubmission'));
    }

    /**
     * Store a newly created KYC submission in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->kyc_status === 'approved' || $user->kyc_status === 'pending') {
            return back()->with('error', 'You already have an active or approved KYC submission.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:255',
            'id_card_front' => 'required|image|mimes:jpeg,png,jpg|max:12048',
            'id_card_back' => 'required|image|mimes:jpeg,png,jpg|max:12048',
            'face_image' => 'required|image|mimes:jpeg,png,jpg|max:12048', // Nayi validation
        ]);

        $frontPath = $request->file('id_card_front')->store('kyc-documents', 'public');
        $backPath = $request->file('id_card_back')->store('kyc-documents', 'public');
        $facePath = $request->file('face_image')->store('kyc-documents', 'public'); // Nayi image save karein

        KycSubmission::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'id_card_number' => $request->id_card_number,
            'id_card_front_url' => $frontPath,
            'id_card_back_url' => $backPath,
            'face_image_url' => $facePath, // Nayi image ka path save karein
            'status' => 'pending',
        ]);

        $user->kyc_status = 'pending';
        $user->save();

        return back()->with('success', 'Your KYC documents have been submitted and are pending approval.');
    }
}
