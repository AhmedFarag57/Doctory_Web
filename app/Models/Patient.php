<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fake_name',
        'wallet'
    ];

    /**
     * Relation with User
     */
    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
