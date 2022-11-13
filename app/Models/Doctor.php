<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable =[
        'd_ID',
        'user_ID',
        'Name',
        'Clinic_Address',
        'Certification',
        'Session_prices',
        'Rating'
    ];
}
