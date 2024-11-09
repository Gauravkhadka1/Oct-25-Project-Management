<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TaskCommentAdded extends Notification
{
    use Queueable;

    protected $task;
    protected $comment;
    protected $username;

    /**
     * Create a new notification instance.
     *
     * @param $task
     * @param $comment
     * @param $username
     */
    public function __construct($task, $comment, $username)
    {
        $this->task = $task;
        $this->comment = $comment;
        $this->username = $username;
    }

    /**
     * Get the notification's delivery channels.
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
                    ->line("{$this->username} added a new comment on the task '{$this->task->name}':")
                    ->line("\"{$this->comment}\"")
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
            'username' => $this->username,
            'comment' => $this->comment,
            'message' => "{$this->username} added a new comment on '{$this->task->name}': \"{$this->comment}\"",
        ];
    }
}
