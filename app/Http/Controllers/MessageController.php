<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    /**
     * Store a newly created message in the conversation.
     *
     * This method handles the creation of a new message within a specific conversation.
     * It associates the authenticated user with the message and allows optional
     * reply-to functionality.
     *
     * @param  StoreMessageRequest  $request  The validated request data
     * @param  Conversation  $conversation  The conversation to add the message to
     * @return RedirectResponse  Redirects back to the previous page
     */
    public function store(StoreMessageRequest $request, Conversation $conversation) : RedirectResponse {

        Gate::authorize('sendMessage', $conversation);      // Authorization check: Only allow if user is a participant
        
        // Create a new message record associated with the given conversation
        $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'reply_to_id' => $request->validated('reply_to_id'),
            'content' => $request->validated('content'),
        ]);

        // Redirect back to the previous page after successful creation
        return back();
    }
}
