<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all conversations that this user participates in.
     * 
     * Defines a many-to-many relationship between users and conversations.
     * The pivot table 'conversation_user' stores the participation records.
     *
     * @return BelongsToMany
     */
    public function conversations(): BelongsToMany {
        return $this->belongsToMany(Conversation::class);
    }

    /**
     * Get all messages sent by this user.
     *
     * @return HasMany
     */
    public function messages(): HasMany {
        return $this->hasMany(Message::class);
    }
}
