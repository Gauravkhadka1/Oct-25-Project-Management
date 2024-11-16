<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MentionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        \Log::info('Notification via method triggered for user: ' . $notifiable->email);
        return ['mail'];
    }
    

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        \Log::info('toMail method executed for user: ' . $notifiable->email);
    
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('You were mentioned')
            ->line('You have been mentioned in a message.')
            ->action('View Message', url('/messages'));
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
