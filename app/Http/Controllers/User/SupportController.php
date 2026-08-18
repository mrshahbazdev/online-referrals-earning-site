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
            'message' => 'required_without:image|nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('support-images', 'public');
        }

        $message = SupportMessage::create([
            'user_id' => Auth::id(),
            'sender' => 'user',
            'message' => $request->message,
            'image_path' => $imagePath,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $this->formatMessage($message),
            ]);
        }

        return redirect()->route('support.index')->with('success', 'Message sent. Admin will reply soon.');
    }

    public function messages(Request $request)
    {
        $lastId = (int) $request->input('last_id', 0);

        SupportMessage::where('user_id', Auth::id())
            ->where('sender', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = SupportMessage::where('user_id', Auth::id())
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
            'image_url' => $message->image_path ? asset('public/uploads/' . $message->image_path) : null,
            'is_read' => $message->is_read,
            'time' => $message->created_at->format('h:i A'),
            'date' => $message->created_at->isToday()
                ? 'Today'
                : ($message->created_at->isYesterday() ? 'Yesterday' : $message->created_at->format('M d, Y')),
            'created_at' => $message->created_at->toDateTimeString(),
        ];
    }
}


