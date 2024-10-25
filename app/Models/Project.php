<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'due_date',
        'status',
    ];
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}