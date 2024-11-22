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
            'project_id',
            'due_date',
            'priority',
        ];
        


    // Task belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
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
