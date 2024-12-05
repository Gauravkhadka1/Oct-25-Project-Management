<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;

class TaskCommentAddedNotification extends Notification
{
    protected $task;
    protected $comment;
    protected $user;

    // Modify the constructor to accept any task type (Task, PaymentTask, or ProspectTask)
    public function __construct($task, $comment, $user)
    {
        $this->task = $task;
        $this->comment = $comment;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];  // Send both mail and store in database notifications
    }

    public function toMail($notifiable)
    {
        $taskType = class_basename($this->task);  // Get the class name of the task dynamically
        return (new MailMessage)
                    ->subject('New Comment Added to Task')
                    ->line($this->user->username . ' added a new comment on ' . $taskType . ' "' . $this->task->name . '":')
                    ->line($this->comment)  // Show the comment itself
                    ->action('View Task', url('dashboard'));
    }

    public function toDatabase($notifiable)
    {
        $taskType = class_basename($this->task);  // Get the class name of the task dynamically
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'user_id' => $this->user->id,  // Include the user who added the comment
            'comment' => $this->comment,
            'message' => $this->user->username . ' added a new comment on ' . $taskType . ' "' . $this->task->name . '"',  // Custom message
        ];
    }
}
