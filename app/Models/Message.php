<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conversation_id',
        'user_id',
        'reply_to_id',
        'content',
    ];

    /**
     * Get the conversation that this message belongs to.
     *
     * @return BelongsTo
     */
    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user who sent this message.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent message that this message is replying to.
     * 
     * This establishes a self-referential relationship where a message
     * can have a parent message (for threaded replies).
     *
     * @return BelongsTo
     */
    public function replyTo(): BelongsTo {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    /**
     * Get all replies to this message.
     * 
     * This is the inverse of the replyTo relationship.
     * It returns all messages that have this message as their parent.
     *
     * @return HasMany
     */
    public function replies(): HasMany {
        return $this->hasMany(Message::class, 'reply_to_id');
    }
}
