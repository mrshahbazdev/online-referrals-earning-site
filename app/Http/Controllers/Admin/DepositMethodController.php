<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class DepositMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $methods = DepositMethod::latest()->paginate(10);
        return view('admin.deposit_methods.index', compact('methods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'qr_code' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            $data['qr_code_url'] = $request->file('qr_code')->store('qrcodes', 'public');
        }

        $method = DepositMethod::create($data);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Deposit Methods',
            'action' => 'Created',
            'description' => 'Admin created a new deposit method: ' . $method->name,
        ]);

        return back()->with('success', 'Deposit method created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DepositMethod $depositMethod)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            if ($depositMethod->qr_code_url) {
                Storage::disk('public')->delete($depositMethod->qr_code_url);
            }
            $data['qr_code_url'] = $request->file('qr_code')->store('qrcodes', 'public');
        }

        $depositMethod->update($data);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Deposit Methods',
            'action' => 'Updated',
            'description' => 'Admin updated deposit method: ' . $depositMethod->name,
        ]);

        return back()->with('success', 'Deposit method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DepositMethod $depositMethod)
    {
        $methodName = $depositMethod->name;

        if ($depositMethod->qr_code_url) {
            Storage::disk('public')->delete($depositMethod->qr_code_url);
        }

        $depositMethod->delete();

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Deposit Methods',
            'action' => 'Deleted',
            'description' => 'Admin deleted deposit method: ' . $methodName,
        ]);

        return back()->with('success', 'Deposit method deleted successfully.');
    }
}
