<?php
namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->subject('New Task Assigned')
                    ->view('emails.tasks.assigned')
                    ->with([
                        'taskName' => $this->task->name,
                        'dueDate' => $this->task->due_date,
                        'assignedBy' => $this->task->assignedBy->username,
                    ]);
    }
}
