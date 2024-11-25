<?php

namespace App\Mail;

use App\Models\ProjectsActivity;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MentionedUserNotificationProject extends Mailable
{
    use Queueable, SerializesModels;

    public $activity;
    public $mentionedUser;
    public $mentioningUser; // New: the user who mentioned
    public $projectName;

    protected $comment;

    // Constructor to pass the necessary data to the mailable
    public function __construct(ProjectsActivity $activity, User $mentionedUser, User $mentioningUser, $projectName,  $comment)
    {
        $this->activity = $activity;
        $this->mentionedUser = $mentionedUser;
        $this->mentioningUser = $mentioningUser;
        $this->projectName = $projectName;
        $this->comment = $comment; 

    }

    public function build()
    {
        // Build the email notification
        return $this->subject('You were mentioned in a comment')
                    ->view('emails.mentioned_user_notification_project') // The view for the email
                    ->with([
                        'taskName' => $this->activity->task->name ?? 'Unknown Task', 
                        'projectName' => $this->projectName,
                        'mentioningUsername' => $this->mentioningUser->username,
                        'comment' => $this->comment, // Include the comment in the email
                    ]);
    }
}
