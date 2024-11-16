<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Prospect;

class ProspectStatusUpdated extends Notification
{
    use Queueable;

    protected $prospect;
    protected $status;

    public function __construct(Prospect $prospect, $status)
    {
        $this->prospect = $prospect;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Prospect Status Updated')
            ->line("The status of prospect {$this->prospect->company_name} has been updated to {$this->status}.")
            ->action('View Prospect', url('/prospects/' . $this->prospect->id))
            ->line('Thank you for using our application!');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'prospect' => $this->prospect,
            'status' => $this->status,
            'message' => "The status of prospect {$this->prospect->company_name} has been updated to {$this->status}.",
        ]);
    }
}
