<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTaskSession extends Model
{
    protected $fillable = ['user_id', 'task_id', 'client_id', 'started_at', 'paused_at'];
    // In your TaskSession model
    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
    ];
    


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function task() {
        return $this->belongsTo(ClientTask::class, 'task_id'); // Ensure task_id is specified
    }
    

    public function client() {
        return $this->belongsTo(Clients::class);
    }
}
