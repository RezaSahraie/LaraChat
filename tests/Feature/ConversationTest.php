<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_view_conversation(): void
    {
        $user = User::create([
            'name' => 'Reza',
            'email' => 'reza@test.com',
            'password' => 'password',
        ]);

        $conversation = Conversation::create([
            'name' => 'Private Conversation',
        ]);

        $conversation->users()->attach($user->id);

        $response = $this
            ->actingAs($user)
            ->get("/chat/{$conversation->id}");

        $response->assertSuccessful();
    }

    public function test_non_member_cannot_view_conversation(): void
    {
        $user = User::create([
            'name' => 'Reza',
            'email' => 'reza@test.com',
            'password' => 'password',
        ]);

        $otherUser = User::create([
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => 'password',
        ]);

        $conversation = Conversation::create([
            'name' => 'Private Conversation',
        ]);

        $conversation->users()->attach($otherUser->id);

        $response = $this
            ->actingAs($user)
            ->get("/chat/{$conversation->id}");

        $response->assertForbidden();
    }
}