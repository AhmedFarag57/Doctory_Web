<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'chat_messages';

    // The guarded -> Primary Key
    protected $guarded = ['id'];

    // The Touches
    protected $touches = ['chat'];


    /**
     * 
     * Relation with User
     */
    public function user() : BelongsTo {

        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 
     * Relation with Chat
     */
    public function chat() : BelongsTo {

        return $this->belongsTo(ChatMessage::class, 'chat_id');
    }

}
