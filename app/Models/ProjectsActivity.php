<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProjectsActivity extends Model

{
   
    protected $fillable = ['task_id', 'details', 'username', 'date', 'user_id', 'profile_pic'];
   


    public function user()
    {
        return $this->belongsTo(User::class);
    }


public function project()
    {
        return $this->belongsTo(Prospect::class, 'project_id');
    }
    public function task()
{
    return $this->belongsTo(Task::class, 'task_id');
}

}
