<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorTime extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'doc_id',
        'startime',
        'endtime',
        'date',
        'reserved',
    ];


    public function doctor() : BelongsTo  {
        return $this->BelongsTo (Doctor::class, 'doc_id');
    }

}
