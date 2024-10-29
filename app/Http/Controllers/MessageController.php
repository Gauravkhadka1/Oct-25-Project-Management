<?php

namespace App\Http\Controllers;

use App\Mail\MessageSent;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\MentionNotification;
use Illuminate\Support\Facades\Notification;




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
        // Ensure the mentioned_user and message fields are required
        $request->validate([
            'message' => 'required|string',
            'mentioned_user' => 'required|string',
        ]);
    
        // Get the mentioned username from the request
        $mentionedUsername = $request->input('mentioned_user');
    
        // Find the user by username
        $user = User::where('username', $mentionedUsername)->first();
    
        // If the user is not found, return an error response
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
    
        // Prepare message data
        $messageData = [
            'content' => $request->input('message'),  // Example of message content
            'mentioned_user' => $user->name,          // The mentioned user's name
        ];
    
        // Send the notification
        Notification::send($user, new MentionNotification($messageData));
    
        // Return a success response
        return response()->json(['message' => 'Message sent successfully!']);
    }
    

}