<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'doc_id',
        'patient_id',
        'status',
        'type',
        'time',
        'report',
    ];
}
