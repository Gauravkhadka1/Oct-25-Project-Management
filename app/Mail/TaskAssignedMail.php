<?php
namespace App\Mail;

use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Constructor accepts any of the Task types
     *
     * @param Task|PaymentTask|ProspectTask $task
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        // Dynamically get the task name based on the task type
        $taskType = class_basename($this->task); // Will return 'Task', 'PaymentTask', or 'ProspectTask'

        return $this->subject('New ' . $taskType . ' Assigned')
                    ->view('emails.task-assigned')
                    ->with([
                        'taskName' => $this->task->name,
                        'dueDate' => $this->task->due_date,
                        'assignedBy' => $this->task->assignedBy->username,
                        'taskType' => $taskType, // Pass the task type to the view
                    ]);
    }
}
