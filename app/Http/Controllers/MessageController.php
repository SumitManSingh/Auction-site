<?php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Chat dashboard + conversations
    public function index()
    {
        $userId = Auth::id();

        $messages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender','receiver'])
            ->get();

        $conversations = $messages->map(function($m) use ($userId){
            $other = $m->sender_id == $userId ? $m->receiver : $m->sender;
            $m->chat_with = $other;
            $m->is_unread = $m->receiver_id == $userId && !$m->is_read;
            return $m;
        })
        ->groupBy(fn($m) => $m->chat_with->user_id)
        ->map(function($group){
            return [
                'user' => $group->first()->chat_with,
                'unread_count' => $group->where('is_unread', true)->count(),
            ];
        });

        $others = User::where('user_id', '!=', $userId)->get();

        return view('messages.inbox', compact('conversations','others'));
    }

    // Fetch messages for a specific user
    public function getMessages(User $user)
    {
        $authId = Auth::id();

        $messages = Message::where(function($q) use ($authId, $user){
                $q->where('sender_id', $authId)->where('receiver_id', $user->user_id);
            })
            ->orWhere(function($q) use ($authId, $user){
                $q->where('sender_id', $user->user_id)->where('receiver_id', $authId);
            })
            ->with(['sender','receiver'])
            ->orderBy('timestamp')
            ->get();

        // Mark messages as read
        Message::where('sender_id', $user->user_id)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // Send a new message
    public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'receiver_id' => 'required|exists:users,user_id',
        'content'     => 'required|string|max:4000',
        'subject'     => 'nullable|string|max:255',
    ]);

    // Create a new message
    $message = new Message();
    $message->sender_id   = Auth::id();
    $message->receiver_id = $validated['receiver_id'];
    $message->subject     = $validated['subject'] ?? 'No Subject';
    $message->content     = $validated['content']; // IDE-friendly
    $message->timestamp   = now();
    $message->is_read     = false;
    $message->save();

    // Broadcast message to Pusher
    broadcast(new MessageSent($message))->toOthers();

    return response()->json($message->load('sender','receiver'));
}
}