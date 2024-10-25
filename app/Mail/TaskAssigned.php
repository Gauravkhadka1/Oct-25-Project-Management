<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $userEmail;

    public function __construct($task, $userEmail)
    {
        $this->task = $task;
        $this->userEmail = $userEmail;
    }

    public function build()
    {
        return $this->view('emails.task_assigned')
                    ->with([
                        'taskName' => $this->task->name,
                        'projectName' => $this->task->project->name,
                    ])
                    ->to($this->userEmail);
    }
}
