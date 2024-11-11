<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTaskSession extends Model
{
    protected $fillable = ['user_id', 'task_id', 'payments_id', 'started_at', 'paused_at'];
    // In your TaskSession model
    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function payment() {
        return $this->belongsTo(Payments::class);
    }
    // In PaymentTaskSession model
public function payment_task()
{
    return $this->belongsTo(PaymentTask::class, 'payment_task_id');
}

}
