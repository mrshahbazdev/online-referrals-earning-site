<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DepositSettingController extends Controller
{
    public function index()
    {
        // Sirf pehla record haasil karein ya naya banayein
        $details = DepositDetail::firstOrCreate(['id' => 1]);
        return view('admin.deposit_settings.index', compact('details'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'network' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $details = DepositDetail::find(1);
        $data = $request->only(['network', 'address']);

        if ($request->hasFile('qr_code')) {
            if ($details->qr_code_url) {
                Storage::disk('public')->delete($details->qr_code_url);
            }
            $path = $request->file('qr_code')->store('qrcodes', 'public');
            $data['qr_code_url'] = $path;
        }

        $details->update($data);

        return back()->with('success', 'Deposit details updated successfully.');
    }
}
