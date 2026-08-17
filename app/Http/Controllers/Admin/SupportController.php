<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->whereHas('supportMessages')
            ->withCount(['supportMessages as unread_count' => function ($query) {
                $query->where('sender', 'user')->where('is_read', false);
            }])
            ->with(['supportMessages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest()
            ->paginate(20);

        return view('admin.support.index', compact('users'));
    }

    public function show(User $user)
    {
        if ($user->role !== 'user') {
            abort(403);
        }

        $messages = SupportMessage::where('user_id', $user->id)
            ->oldest()
            ->get();

        SupportMessage::where('user_id', $user->id)
            ->where('sender', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.support.show', compact('user', 'messages'));
    }

    public function reply(Request $request, User $user)
    {
        if ($user->role !== 'user') {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        SupportMessage::create([
            'user_id' => $user->id,
            'sender' => 'admin',
            'message' => $request->message,
        ]);

        return redirect()->route('admin.support.show', $user)->with('success', 'Reply sent.');
    }
}
