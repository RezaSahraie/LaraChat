<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    /**
     * Display a list of all conversations for the chat interface.
     *
     * This method fetches all conversations with their related users and
     * the latest message in each conversation. The conversations are sorted
     * by their last update time, showing the most recently active first.
     * Uses Inertia.js to render the Vue/React component with the data.
     *
     * @return Response The Inertia response rendering the Chat/Index view
     */
    public function index(): Response
    {
        // Fetch all conversations with eager loading
        $conversations = Conversation::query()
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])->latest('updated_at')->get();

        
        // Return the Inertia response
        return Inertia::render('Chat/Index', [
            'conversations' => $conversations,
        ]);
    }

    public function show(Conversation $conversation): Response{
        Gate::authorize('view', $conversation);     // Authorization check: Only allow if user is a participant

        $conversation->load([
            'users',
            'messages.user',
            'messages.replyTo',
        ]);

        return Inertia::render('Chat/Index', [
            'conversations' => Conversation::query()
                ->with([
                    'users',
                    'messages' => fn ($query) => $query
                        ->latest()
                        ->limit(1),
                ])
                ->latest('updated_at')
                ->get(),

            'selectedConversation' => $conversation,
        ]);
    }
}
