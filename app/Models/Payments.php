<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'category',
        'contact_person',
        'phone',
        'email',
        'amount',
        'due_date',
        'activities',
    ];
    protected $casts = [
        'amount' => 'float',
    ];
    public function payment_tasks()
    {
        return $this->hasMany(PaymentTask::class, 'payments_id', 'id');
    }
}
