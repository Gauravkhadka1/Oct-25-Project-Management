<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentsActivity extends Model
{
    protected $fillable = ['payments_id', 'details', 'username', 'date', 'user_id', 'profile_pic'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payment()
    {
        return $this->belongsTo(Payments::class, 'payments_id');
    }
}
