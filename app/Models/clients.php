<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'website',
        'address',
        'company_phone',
        'company_email',
        'contact_person',
        'contact_person_phone',
        'contact_person_email',
        'category',
        'subcategory',
        'additional_subcategory',
        'contract',
        'seo_contract',
        'maintenance_contract',

        'domain_active_date',
        'domain_expiry_date',
        'domain_amount',
        'hosting_active_date',
        'hosting_expiry_date',
        'hosting_space',
        'hosting_type',
        'hosting_amount',
        'microsoft_active_date',
        'microsoft_expiry_date',
        'microsoft_type',
        'no_of_license',
        'microsoft_amount',
        'maintenance_active_date',
        'maintenance_expiry_date',
        'maintenance_type',
        'maintenance_amount',
        'seo_active_date',
        'seo_expiry_date',
        'seo_type',
        'seo_amount',
        'vat_no',
        'additional_info',
    ];
}
