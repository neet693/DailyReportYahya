<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $authId = auth()->id();

        $chatUsers = User::where('id', '!=', $authId)
            ->withCount(['receivedMessages as unread_count' => function ($query) use ($authId) {
                $query->where('receiver_id', $authId)->where('is_read', false);
            }])
            ->get();

        return view('chats.modal', compact('chatUsers'));
    }

    public function fetchMessages(Request $request)
    {
        $messages = Message::where(function ($q) use ($request) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $request->receiver_id);
        })->orWhere(function ($q) use ($request) {
            $q->where('sender_id', $request->receiver_id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        // Tandai sudah dibaca
        Message::where('sender_id', $request->receiver_id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // 🔹 Jangan pakai ->toOthers(), biar pengirim juga dapat broadcast
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}
