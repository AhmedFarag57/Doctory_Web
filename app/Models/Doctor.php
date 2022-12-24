<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    use HasFactory;


    protected $fillable =[
        'user_id',
        'clinic_address',
        'certifications',
        'session_price',
        'rating'
    ];

    /**
     * Relation with User
     */
    public function user() : BelongsTo {

        return $this->belongsTo(User::class, 'user_id');
    }
}
