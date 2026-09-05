<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an authenticated user can successfully send a message
     * to an existing conversation they are a participant of.
     *
     * This test verifies:
     * - The user is authenticated and has access to the conversation
     * - The message is properly stored in the database
     * - The user is redirected back after sending the message
     *
     * @return void
     */
    public function test_user_can_send_a_message_to_a_conversation(): void
    {
        /**
         * ARRANGE: Prepare the test environment
         */
        // Create a test user using the User factory
        $user = User::factory()->create();

        // Create a new private conversation without a specific name
        $conversation = Conversation::create([
            'type' => 'private',
            'name' => 'Test Conversaition',
        ]);

        // Attach the user to the conversation as a participant
        $conversation->users()->attach($user);




        /**
         * ACT: Execute the functionality being tested
         */
        // Simulate an authenticated request as the created user
        $response = $this->actingAs($user)->post(
            route('conversations.messages.store', $conversation),
            [
                'content' => 'Hello LaraChat!',
            ]
        );




        /**
         * ASSERT: Verify the results
         */
        // Assert that the response redirects back (HTTP 302) - This indicates the message was processed successfully
        $response->assertRedirect();
        // Verify that the message was actually saved in the database
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'content' => 'Hello LaraChat!',
        ]);
    }


    /**
     * Test that a user who is not a member of a conversation
     * cannot send messages to that conversation.
     *
     * This test verifies the authorization logic that restricts message
     * sending to only participants of the conversation. It ensures that:
     * 1. Non-members receive a 403 Forbidden response
     * 2. The message is not stored in the database
     *
     * @return void
     */
    public function test_non_member_cannot_send_message_to_conversation(): void
    {
        /**
         * ARRANGE: Prepare the test environment
         */
        // Create the first user (non-member)
        $user = User::create([
            'name' => 'Reza',
            'email' => 'reza@test.com',
            'password' => 'password',
        ]);

        // Create a second user (will be the conversation member)
        $otherUser = User::create([
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => 'password',
        ]);

        // Create a new conversation
        $conversation = Conversation::create([
            'name' => 'Private Conversation',
        ]);

        // Add only the otherUser to the conversation - Note: $user is NOT attached to this conversation
        $conversation->users()->attach($otherUser->id);

        /**
         * ACT: Attempt to send a message as a non-member
         */
        // Simulate an authenticated request as $user (non-member)
        $response = $this
            ->actingAs($user)       // Authenticate as the non-member user
            ->post("/conversations/{$conversation->id}/messages", [
                'content' => 'Unauthorized message',
            ]);

        /**
         * ASSERT: Verify the results
         */
        // Assert that the response is a 403 Forbidden - This confirms the authorization check is working
        $response->assertForbidden();

        // Assert that the unauthorized message was NOT saved to the database
        $this->assertDatabaseMissing('messages', [
            'content' => 'Unauthorized message',
        ]);
    }
}