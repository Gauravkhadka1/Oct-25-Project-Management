<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'client_id', 'assigned_to', 'due_date', 'priority', 'status',
    ];
}
