<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with(['level', 'referrer']);

        // Search Logic
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('username', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Sorting Logic
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        if ($sortBy == 'level') {
            $query->join('levels', 'users.level_id', '=', 'levels.id')
                ->orderBy('levels.upgrade_cost', $sortDirection)
                ->select('users.*'); // Avoid column name conflicts
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $users = $query->paginate(10)->withQueryString();
        $levels = Level::all();

        return view('admin.users.index', compact('users', 'levels'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referral_code' => Str::random(8),
            'level_id' => $request->level_id ?? 1,
            'balance' => $request->balance ?? 0,
        ]);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'User Management',
            'action' => 'User Created',
            'description' => 'Admin created a new user: ' . $user->username
        ]);

        return back()->with('success', 'User created successfully.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'level_id' => ['required', 'exists:levels,id'],
            'balance' => ['required', 'numeric', 'min:0'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'mobile_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'kyc_status' => ['required', 'in:unverified,pending,approved,rejected'],
        ]);

        // Tamam fields ko update karein
        $user->update($request->only([
            'username', 'email', 'level_id', 'balance',
            'first_name', 'last_name', 'mobile_number', 'address', 'kyc_status'
        ]));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $username = $user->username; // Save the username before deleting
        $user->delete();

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'log_type' => 'User Management',
            'action' => 'User Deleted',
            'description' => 'Admin deleted user: ' . $username
        ]);

        return back()->with('success', 'User deleted successfully.');
    }
}
