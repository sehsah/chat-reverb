<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSent;

class ChatController extends Controller
{

    public function index()
    {
        $messages = Message::latest()
            ->take(100)
            ->get()
            ->reverse();
        
        $users = User::where('id', '!=', Auth::id())
            ->latest()
            ->get();

        return view('chat.index', compact('messages', 'users'));
    }

    public function show(Conversation $conversation)
    {
        // Check if user is participant
        if (!$conversation->participants->contains(Auth::id())) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->latest()
            ->paginate(50);

        $otherParticipant = $conversation->participants()
            ->where('users.id', '!=', Auth::id())
            ->first();

        return view('chat.show', compact('conversation', 'messages', 'otherParticipant'));
    }

    public function store(Request $request)
    {
        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
        $message['sender'] = auth()->user();
        broadcast(new MessageSent($message->load('user')));
    
        return response()->json(['message' => $message]);
    }

    public function createConversation(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        // Check if conversation already exists
        $existingConversation = DB::table('conversation_user')
            ->select('conversation_id')
            ->where('user_id', Auth::id())
            ->whereIn('conversation_id', function($query) use ($validated) {
                $query->select('conversation_id')
                    ->from('conversation_user')
                    ->where('user_id', $validated['user_id']);
            })
            ->first();

        if ($existingConversation) {
            $conversation = Conversation::find($existingConversation->conversation_id);
        } else {
            $conversation = DB::transaction(function() use ($validated) {
                $conversation = Conversation::create([
                    'name' => null,
                    'is_public' => false
                ]);

                // Add participants
                $conversation->participants()->attach([
                    Auth::id(),
                    $validated['user_id']
                ]);

                return $conversation;
            });
        }

        // Create the first message
        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'content' => $validated['message']
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'message' => $message->load('user'),
            'status' => 'success'
        ]);
    }

    public function markAsRead(Conversation $conversation)
    {
        // Mark all unread messages in this conversation as read
        $conversation->messages()
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    public function getUsers(Request $request)
    {
        $query = $request->get('query', '');
        
        $users = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->take(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}
