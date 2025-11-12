<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminManagementController extends Controller
{
    public function index()
    {
        // Tamam admins ki list, siwaye current admin ke
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'level_id' => 1, // Default level
        ]);

        return back()->with('success', 'Admin created successfully.');
    }

    public function destroy(User $admin)
    {
        // Yaqeen karein ke koi doosra admin hi delete ho raha hai
        if ($admin->id === Auth::id()) {
            return back()->withErrors('You cannot delete your own account.');
        }
        $admin->delete();
        return back()->with('success', 'Admin deleted successfully.');
    }
}
