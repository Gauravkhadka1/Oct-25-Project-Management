<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTaskSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'payment_task_id', 'payment_id', 'started_at', 'paused_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentTask()
    {
        return $this->belongsTo(PaymentTask::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payments::class);
    }
}
