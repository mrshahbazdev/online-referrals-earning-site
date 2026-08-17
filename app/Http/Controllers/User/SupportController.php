<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $messages = SupportMessage::where('user_id', Auth::id())
            ->oldest()
            ->get();

        SupportMessage::where('user_id', Auth::id())
            ->where('sender', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('support.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        SupportMessage::create([
            'user_id' => Auth::id(),
            'sender' => 'user',
            'message' => $request->message,
        ]);

        return redirect()->route('support.index')->with('success', 'Message sent. Admin will reply soon.');
    }
}
