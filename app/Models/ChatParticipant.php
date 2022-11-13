<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'chat_participants';

    // The guarded -> Primary Key
    protected $guarded = ['id'];


    /**
     * 
     * Relation with User
     */
    public function user() : BelongsTo {

        return $this->belongsTo(User::class, 'user_id');
    }
}
