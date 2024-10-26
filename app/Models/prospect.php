<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'phone_number',
        'email',
        'message',
        'inquirydate',
        'probability',
        'activities',
        'status',
        
    ];
    protected $casts = [
        'inquirydate' => 'datetime', // Cast inquirydate to Carbon instance
    ];
}
