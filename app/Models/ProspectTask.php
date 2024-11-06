<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProspectTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'assigned_to',
        'assigned_by',
        'start_date',
        'due_date',
        'priority',
        'prospect_id',
    ];

    // Task belongs to a project
    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
    public function assignedUser()
{
    return $this->belongsTo(User::class, 'assigned_to', 'id');
}

public function assignedBy()
{
    return $this->belongsTo(User::class, 'assigned_by', 'id');
}

}
