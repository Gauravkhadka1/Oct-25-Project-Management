<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'company_phone',
        'company_email',
        'contact_person',
        'contact_person_phone',
        'contact_person_email',
        'category',
        'subcategory',
        'additional_subcategory',
    ];
}
