<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('query');

    if (empty($query)) {
        // Fetch all users if no query is provided
        $users = User::all();
    } else {
        // Fetch users where username starts with the query
        $users = User::where('username', 'like', $query . '%')->get();
    }

    return response()->json($users);
}
public function notifyMention(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'message' => 'required|string',
    ]);

    // Find the user by username
    $user = User::where('username', $request->username)->first();

    if ($user) {
        // Send email notification
        Mail::to($user->email)->send(new MentionNotification($request->username, $request->message));

        return response()->json(['message' => 'Notification sent successfully.']);
    }

    return response()->json(['message' => 'User not found.'], 404);
}
}
