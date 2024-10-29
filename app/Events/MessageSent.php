<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, SerializesModels;

    public $message;
    public $mentionedUser;

    /**
     * Create a new event instance.
     *
     * @param  string  $message
     * @param  string  $mentionedUser
     * @return void
     */
    public function __construct($message, $mentionedUser)
    {
        $this->message = $message;
        $this->mentionedUser = $mentionedUser;
    }
}
