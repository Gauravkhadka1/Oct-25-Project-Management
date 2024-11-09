<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Task;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    // Define how the notification will be delivered
    public function via($notifiable)
    {
        return ['database']; // You can add ['mail'] if you want to send an email too
    }

    // Define the notification data to be stored in the database
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'assigned_by' => $this->task->assignedBy->username,
            'message' => 'A new task has been assigned to you: ' . $this->task->name,  // Add the message key
        ];
    }

    // Optionally, you can use the toMail method to send emails
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->line('A task has been assigned to you: ' . $this->task->name)
            ->action('View Task', url('/tasks/' . $this->task->id));
    }
}
