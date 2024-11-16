<?php

namespace App\Mail;

use App\Models\PaymentsActivity;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MentionedUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $activity;
    public $mentionedUser;

    // Constructor to pass the necessary data to the mailable
    public function __construct(PaymentsActivity $activity, User $mentionedUser)
    {
        $this->activity = $activity;
        $this->mentionedUser = $mentionedUser;
    }

    public function build()
    {
        // Build the email notification
        return $this->subject('You were mentioned in an activity')
                    ->view('emails.mentioned_user_notification') // The view for the email
                    ->with([
                        'activityDetails' => $this->activity->details,
                        'username' => $this->mentionedUser->username,
                        'activityLink' => route('payments.show', $this->activity->payments_id), // Link to the activity
                    ]);
    }
}
