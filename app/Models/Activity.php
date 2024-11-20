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

    public function likes()
{
    return $this->hasMany(Like::class);
}

public function replies()
{
    return $this->hasMany(Reply::class);
}

public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }
}
