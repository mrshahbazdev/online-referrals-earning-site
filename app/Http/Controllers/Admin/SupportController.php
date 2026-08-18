<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;

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
            'message' => 'required_without:image|nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('support-images', 'public');
        }

        $message = SupportMessage::create([
            'user_id' => $user->id,
            'sender' => 'admin',
            'message' => $request->message,
            'image_path' => $imagePath,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $this->formatMessage($message),
            ]);
        }

        return redirect()->route('admin.support.show', $user)->with('success', 'Reply sent.');
    }

    public function messages(Request $request, User $user)
    {
        if ($user->role !== 'user') {
            abort(403);
        }

        $lastId = (int) $request->input('last_id', 0);

        SupportMessage::where('user_id', $user->id)
            ->where('sender', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = SupportMessage::where('user_id', $user->id)
            ->where('id', '>', $lastId)
            ->oldest()
            ->get();

        return response()->json([
            'data' => $messages->map(fn ($msg) => $this->formatMessage($msg)),
        ]);
    }

    private function formatMessage(SupportMessage $message)
    {
        return [
            'id' => $message->id,
            'sender' => $message->sender,
            'message' => $message->message,
            'image_url' => $message->image_path ? asset('uploads/' . $message->image_path) : null,
            'is_read' => $message->is_read,
            'time' => $message->created_at->format('h:i A'),
            'date' => $message->created_at->isToday()
                ? 'Today'
                : ($message->created_at->isYesterday() ? 'Yesterday' : $message->created_at->format('M d, Y')),
            'created_at' => $message->created_at->toDateTimeString(),
        ];
    }
}

