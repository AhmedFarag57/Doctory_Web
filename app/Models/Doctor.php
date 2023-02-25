<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;


    protected $fillable =[
        'user_id',
        'clinic_address',
        'session_price',
        'rating',
        'accepted'
    ];

    /**
     * Relation with User
     */
    public function user() : BelongsTo {

        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation with Certification
     */
    public function certification() : HasMany {
        return $this->hasMany(Certification::class, 'doc_id');
    }

    /**
     * Relation with Appointment
     */
    public function appointment() : HasMany{
        return $this->hasMany(Appointment::class, 'doc_id');
    }
}
