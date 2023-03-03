<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_id',
        'patient_id',
        'status',
        'session_price',
        'time',
        'date'
    ];

    /**
     * Relation with Doctor
     */
    public function doctor() : BelongsTo{
        return $this->belongsTo(Doctor::class, 'doc_id');
    }

    /**
     * Relation with Patient
     */
    public function patient() : BelongsTo{
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
