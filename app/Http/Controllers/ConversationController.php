<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ConversationController extends Controller
{
    /**
     * Display the form for creating a new private conversation.
     *
     * This method fetches all users except the currently authenticated user
     * and renders them in a selection list for starting a private chat.
     * Users are sorted alphabetically by name for better UX.
     *
     * @return Response The Inertia response rendering the Chat/Create view
     */
    public function create(): Response {
        // Fetch all users except the currently authenticated user
        $users = User::query()->select('id', 'name')->whereKeyNot(Auth::id())
            ->orderBy('name')->get();

        // Render the create conversation form with the list of users
        return Inertia::render('Chat/Create', [
            'users' => $users,
        ]);
    }

    /**
     * Create a new private conversation with another user.
     *
     * This method validates the request, creates a new private conversation,
     * and attaches both the authenticated user and the selected user
     * as participants. The user is then redirected to the newly created
     * conversation.
     *
     * @param Request $request The incoming HTTP request with the user_id
     * @return RedirectResponse Redirects to the newly created conversation
     */
    public function store(Request $request): RedirectResponse {

        // Validate the incoming request
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        // Create a new private conversation
        $conversation = Conversation::create([
            'type' => 'private',
            'name' => User::find($validated['user_id'])->name,
        ]);

        // Attach both users to the conversation as participants
        $conversation->users()->attach([
            Auth::id(),
            $validated['user_id'],
        ]);

        // Redirect to the newly created conversation's show page
        return redirect()->route('chat.show', $conversation);
    }
}