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

    /**
     * Create a new notification instance.
     *
     * @param $task
     * @param $newStatus
     */
    public function __construct($task, $newStatus)
    {
        $this->task = $task;
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
                    ->line("The status of the task '{$this->task->name}' has been updated to '{$this->newStatus}'.")
                    ->action('View Task', url('/tasks/'.$this->task->id))
                    ->line('Thank you for using our application!');
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
            'message' => "The status of the task '{$this->task->name}' has been updated to '{$this->newStatus}'.",
        ];
    }
}
