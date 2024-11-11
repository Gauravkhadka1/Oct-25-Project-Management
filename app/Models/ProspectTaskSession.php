<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectTaskSession extends Model
{
    protected $fillable = ['user_id', 'task_id', 'prospect_id', 'started_at', 'paused_at'];
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

    public function prospect() {
        return $this->belongsTo(Prospect::class);
    }
}
