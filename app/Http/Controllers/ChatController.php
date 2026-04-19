<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Workspace;
use App\Models\Conversation;
use App\Services\GeminiChatService;

class ChatController extends Controller
{
    public function store(Request $request, Workspace $workspace)
    {
        if ($workspace->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['message' => 'required|string']);

        // Find or create conversation for this workspace
        $conversation = $workspace->conversations()->latest()->first();
        if (!$conversation) {
            $conversation = $workspace->conversations()->create(['title' => 'Chat ' . now()->format('Y-m-d')]);
        }

        // Save user message
        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'content' => $request->message
        ]);

        // Build history
        $history = [];
        foreach ($conversation->messages()->oldest()->get() as $msg) {
            if ($msg->id === $userMessage->id) { continue; } // Don't add current user msg to history
            $history[] = [
                'role' => $msg->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg->content]]
            ];
        }

        // Get document context
        $documentContext = $workspace->documents()->pluck('content')->implode("\n\n---\n\n");
        if (empty($documentContext)) {
            $documentContext = "No documents uploaded yet.";
        }

        // Call Gemini
        $gemini = app(GeminiChatService::class);
        $reply = $gemini->sendMessage($history, $documentContext, $request->message);

        // Save AI response
        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $reply
        ]);

        return redirect()->back();
    }
}
