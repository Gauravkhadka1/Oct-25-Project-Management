<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['prospect_id', 'details', 'username', 'date', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
