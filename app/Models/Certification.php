<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_id',
        'title',
        'image'
    ];

    /**
     * Relation with Doctor
     */
    public function doctor() : BelongsTo {
        return $this->belongsTo(Doctor::class, 'doc_id');
    }

}
