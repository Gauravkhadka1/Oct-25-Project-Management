<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clients extends Model
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
       'website_status',
       'issues',
       'hosting_start',
       'hosting_end',
    ];
    protected $casts = [
        'web_hosting_start_date' => 'date',
        'web_hosting_end_date' => 'date',
    ];
}
