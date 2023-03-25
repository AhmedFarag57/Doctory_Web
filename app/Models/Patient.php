<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
       // 'fake_name',
        'wallet'
    ];

    /**
     * Relation with User
     */
    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation with Appointment
     */
    public function appointment() : HasMany{
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
