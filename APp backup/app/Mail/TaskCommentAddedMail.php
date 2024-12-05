<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskCommentAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $task;
    protected $comment;

    public function __construct(Task $task, $comment)
    {
        $this->task = $task;
        $this->comment = $comment;
    }

    public function build()
    {
        return $this->subject('New Comment Added to Task')
                    ->view('emails.task-comment-added')
                    ->with([
                        'taskName' => $this->task->name,
                        'comment' => $this->comment,
                        'taskUrl' => url('/tasks/' . $this->task->id),
                    ]);
    }
}
