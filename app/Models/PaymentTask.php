<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'assigned_to',
        'assigned_by',
        'start_date',
        'due_date',
        'priority',
        'payments_id',
    ];

    // Task belongs to a project
    public function payment()
    {
        return $this->belongsTo(PaymentTask::class);
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
    protected $casts = [
        'start_date',
        'due_date' => 'datetime', // Cast inquirydate to Carbon instance
    ];
}
