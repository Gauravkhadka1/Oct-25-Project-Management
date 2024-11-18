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
    public $mentioningUser; // New: the user who mentioned
    public $companyName;// New: payment ID

    // Constructor to pass the necessary data to the mailable
    public function __construct(PaymentsActivity $activity, User $mentionedUser, User $mentioningUser,  $companyName)
    {
        $this->activity = $activity;
        $this->mentionedUser = $mentionedUser;
        $this->mentioningUser = $mentioningUser;
        $this->companyName = $companyName;

    }

    public function build()
    {
        // Build the email notification
        return $this->subject('You were mentioned in a comment')
                    ->view('emails.mentioned_user_notification') // The view for the email
                    ->with([
                        'activityDetails' => $this->activity->details,
                        'mentionedUsername' => $this->mentionedUser->username,
                        'mentioningUsername' => $this->mentioningUser->username, // Pass mentioning user
                        'companyName' => $this->companyName,
                        'activityLink' => route('payments.show', $this->activity->payments_id),
                    ]);
    }
}
