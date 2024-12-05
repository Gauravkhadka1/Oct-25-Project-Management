<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'client_id', 'assigned_to', 'assigned_by','due_date', 'priority', 'status',
    ];
    public function client()
{
    return $this->belongsTo(Clients::class);
}
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }


}
