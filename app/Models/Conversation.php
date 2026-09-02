<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
    ];

    /**
     * Get all users participating in this conversation.
     * 
     * This defines a many-to-many relationship between conversations and users.
     * The pivot table 'conversation_user' stores the participants.
     *
     * @return BelongsToMany
     */
    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get all messages in this conversation.
     *
     * @return HasMany
     */
    public function messages(): HasMany {
        return $this->hasMany(Message::class);
    }
}
