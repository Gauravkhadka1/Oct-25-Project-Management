<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskStatusUpdated extends Notification
{
    use Queueable;

    protected $task;
    protected $newStatus;
    protected $username;

    /**
     * Create a new notification instance.
     *
     * @param $task
     * @param $newStatus
     * @param $username
     */
    public function __construct($task, $newStatus, $username)
    {
        $this->task = $task;
        $this->username = $username;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->line("{$this->username} updated the status of '{$this->task->name}' to '{$this->newStatus}'.")
        ->action('View Task', url('/tasks/'.$this->task->id));
                  
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'new_status' => $this->newStatus,
            'username' => $this->username,
            'message' => "{$this->username} updated the status of '{$this->task->name}' to '{$this->newStatus}'.",
        ];
    }
}
