<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'assigned_to',
        'assigned_by',
        'start_date',
        'due_date',
        'priority',
        'project_id',
    ];

    // Task belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}