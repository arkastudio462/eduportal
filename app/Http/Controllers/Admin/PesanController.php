<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\PesanBaruNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PesanController extends Controller
{
    public function index()
    {
        $conversations = Conversation::whereHas('participants', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['participants', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->latest('last_message_at')
            ->get();

        return view('admin.pesan', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $conversation->load('participants');

        if (! $conversation->participants->contains(Auth::id())) {
            abort(403);
        }

        $conversation->load('messages.sender');

        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $users = User::where('id', '!=', Auth::id())->get();

        return view('admin.pesan-show', compact('conversation', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subjek' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'recipient_id' => 'required|exists:users,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip,rar|max:10240',
        ]);

        $conversation = Conversation::create([
            'subjek' => $request->subjek,
            'created_by' => Auth::id(),
            'last_message_at' => now(),
        ]);

        $conversation->participants()->attach([Auth::id(), $request->recipient_id]);
        $conversation->load('participants');

        $fileData = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileData = [
                'file_path' => $file->store('messages', 'public'),
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ];
        }

        $message = Message::create(array_merge([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->body ?? $request->file?->getClientOriginalName() ?? '',
        ], $fileData));

        $message->load('sender');
        $recipient = User::find($request->recipient_id);
        Notification::send($recipient, new PesanBaruNotification($message, $conversation));

        broadcast(new MessageSent($message, $conversation))->toOthers();

        return redirect()->route('admin.pesan.show', $conversation);
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $conversation->load('participants');

        if (! $conversation->participants->contains(Auth::id())) {
            abort(403);
        }

        $request->validate([
            'body' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip,rar|max:10240',
        ]);

        $fileData = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileData = [
                'file_path' => $file->store('messages', 'public'),
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ];
        }

        $message = Message::create(array_merge([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->body ?? $request->file?->getClientOriginalName() ?? '',
        ], $fileData));

        $conversation->update(['last_message_at' => now()]);

        $message->load('sender');
        $others = $conversation->participants->where('id', '!=', Auth::id());
        Notification::send($others, new PesanBaruNotification($message, $conversation));

        broadcast(new MessageSent($message, $conversation))->toOthers();

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $message->id,
                'body' => $message->body,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'created_at' => $message->created_at->toIso8601String(),
                'file_url' => $message->fileUrl(),
                'file_name' => $message->file_name,
                'file_type' => $message->file_type,
                'is_image' => $message->isImage(),
            ]);
        }

        return back();
    }

    public function update(Request $request, Conversation $conversation, Message $message)
    {
        $conversation->load('participants');

        if (! $conversation->participants->contains(Auth::id())) {
            abort(403);
        }

        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }

        $request->validate(['body' => 'required|string']);

        $message->update(['body' => $request->body]);

        return response()->json([
            'id' => $message->id,
            'body' => $message->body,
            'updated_at' => $message->updated_at->toIso8601String(),
        ]);
    }

    public function bulkDestroy(Request $request, Conversation $conversation)
    {
        $conversation->load('participants');

        if (! $conversation->participants->contains(Auth::id())) {
            abort(403);
        }

        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        $deleted = Message::whereIn('id', $request->ids)
            ->where('conversation_id', $conversation->id)
            ->where('sender_id', Auth::id())
            ->delete();

        return response()->json(['deleted' => $deleted]);
    }
}
