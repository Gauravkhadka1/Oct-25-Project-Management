<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectTaskSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'prospect_task_id', 'prospect_id', 'started_at', 'paused_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prospectTask()
    {
        return $this->belongsTo(ProspectTask::class);
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}
