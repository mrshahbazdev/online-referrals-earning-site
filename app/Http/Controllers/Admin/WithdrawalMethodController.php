<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class WithdrawalMethodController extends Controller
{
    public function index()
    {
        $methods = WithdrawalMethod::latest()->paginate(10);
        return view('admin.withdrawal_methods.index', compact('methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        WithdrawalMethod::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Withdrawal Method Management',
            'action' => 'Method Added',
            'description' => 'Added new withdrawal method: ' . $request->name,
        ]);

        return back()->with('success', 'Withdrawal method added successfully.');
    }

    public function update(Request $request, WithdrawalMethod $withdrawalMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $withdrawalMethod->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Withdrawal Method Management',
            'action' => 'Method Updated',
            'description' => 'Updated withdrawal method: ' . $withdrawalMethod->name,
        ]);

        return back()->with('success', 'Withdrawal method updated successfully.');
    }

    public function destroy(WithdrawalMethod $withdrawalMethod)
    {
        $name = $withdrawalMethod->name;
        $withdrawalMethod->delete();

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'Withdrawal Method Management',
            'action' => 'Method Deleted',
            'description' => 'Deleted withdrawal method: ' . $name,
        ]);

        return back()->with('success', 'Withdrawal method deleted successfully.');
    }
}
