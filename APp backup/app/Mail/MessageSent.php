<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class MessageSent extends Mailable
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.message')
                    ->with('data', $this->data);
    }
}
