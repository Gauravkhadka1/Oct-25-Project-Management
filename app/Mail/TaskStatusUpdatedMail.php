<?php 
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $task;
    protected $status;

    public function __construct(Task $task, $status)
    {
        $this->task = $task;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Task Status Updated')
                    ->view('emails.task-status-updated')
                    ->with([
                        'taskName' => $this->task->name,
                        'status' => $this->status,
                        'taskUrl' => url('/tasks/' . $this->task->id),
                    ]);
    }
}
