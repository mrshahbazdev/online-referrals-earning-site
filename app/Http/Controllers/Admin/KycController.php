<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = KycSubmission::with('user')->latest();

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('full_name', 'like', "%{$search}%")
              ->orWhere('id_number', 'like', "%{$search}%");
        }

        $submissions = $query->paginate(10);
        return view('admin.kyc.index', compact('submissions', 'search'));
    }

    public function update(Request $request, KycSubmission $kycSubmission)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Update the submission status
        $kycSubmission->update(['status' => $request->status]);

        // Update the user's KYC status
        if ($kycSubmission->user) {
            $kycSubmission->user->update(['kyc_status' => $request->status]);
        }

        // Log the admin activity
        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'KYC Management',
            'action' => 'KYC Status Updated',
            'description' => 'Admin ' . $request->status . ' KYC for user: ' . $kycSubmission->user->username,
        ]);

        return back()->with('success', 'KYC status updated successfully.');
    }
}
