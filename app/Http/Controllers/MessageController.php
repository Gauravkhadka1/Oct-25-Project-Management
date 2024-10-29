<?php

namespace App\Http\Controllers;

use App\Mail\MessageSent;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;



class MessageController extends Controller
{
    public function store(Request $request) {
        // Validate the incoming request
        $request->validate([
            'message' => 'required|string',
            'mentioned_user' => 'required|string', // Make sure this is required
        ]);
    
        // Your logic to handle the message and the mentioned user
        // ...
    
        // Return a JSON response for successful message sending
        return response()->json(['message' => 'Message sent successfully!']);
    }
    
    public function submitMessage(Request $request)
{
    $user = User::find($request->user_id); 
    $data = $request->all();  // or whatever data you need to pass
    Mail::to($user->email)->send(new MentionNotification(['mentionMessage' => $messageContent]));
    return view('emails.message', ['user' => $user, 'message' => $request->message]);

}

}