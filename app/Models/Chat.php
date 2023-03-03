<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'chats';

    // The guarded -> Primary Key
    protected $guarded = ['id'];



    /**
     * 
     * Relation with ChatParticipant
     */
    public function participants() : HasMany {

        return $this->hasMany(ChatParticipant::class, 'chat_id');   
    }

    /**
     * 
     * Relation whit Message
     */
    public function messages() : HasMany {

        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    /**
     * 
     * The last Message in the chat
     */
    public function lastMessage() : HasOne {

        return $this->hasOne(ChatMessage::class, 'chat_id')->latest('updated_at');
    }


    public function scopeHasParticipant($query, int $userId){
        return $query->whereHas('participants', function($q) use ($userId){
            $q->where('user_id', $userId);
        });
    }


}
