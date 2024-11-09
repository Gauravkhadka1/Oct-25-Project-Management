<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;

class TaskStatusUpdatedNotification extends Notification
{
    protected $task;
    protected $status;

    public function __construct($task, $status)
    {
        $this->task = $task;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];  // Send both mail and store in database notifications
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Task Status Updated')
                    ->line('The status of task "' . $this->task->name . '" has been updated to ' . $this->status . '.')
                    ->action('View Task', url('/tasks/' . $this->task->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'status' => $this->status,
            'message' => 'The status of the task "' . $this->task->name . '" has been updated to "' . $this->status . '"', // Custom message
        ];
    }
}
