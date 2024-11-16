<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Payments;

class PaymenttsStatusUpdated extends Notification
{
    use Queueable;

    protected $payment;
    protected $status;

    public function __construct(Payments $payment, $status)
    {
        $this->payment = $payment;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Status Updated')
            ->line("The status of payment {$this->payment->company_name} has been updated to {$this->status}.")
            ->action('View Payment', url('/payemnts/' . $this->payment->id))
            ->line('Thank you for using our application!');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'payment' => $this->payment,
            'status' => $this->status,
            'message' => "The status of payment {$this->payment->company_name} has been updated to {$this->status}.",
        ]);
    }
}
