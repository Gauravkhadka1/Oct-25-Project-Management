<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'due_date',
        'status',
        'sub-status',
    ];
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function calculateTimeLeft()
{
    $dueDate = Carbon::parse($this->due_date);
    $currentDate = Carbon::now();
    $this->time_left = $dueDate->diffInDays($currentDate, false); // false ensures accurate positive/negative values
    $this->save();
}

}
