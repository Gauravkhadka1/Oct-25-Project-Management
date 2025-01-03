<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\MentionedUserNotification; // Create the mail class for notifications
use App\Models\PaymentsActivity;
use App\Models\User;

class PaymentsActivityController extends Controller
{
    public function store(Request $request)
    {
        // Create a new activity record
        $activity = new PaymentsActivity();
        $activity->payments_id = $request->input('payments_id');
        $activity->details = $request->input('message'); // Store the message with the mention
        $activity->user_id = Auth::id();  // Store the authenticated user's ID
        $user = Auth::user();
      // Store the relative path to profile picture
$activity->profile_pic = $user && $user->profilepic
? 'profilepics/' . $user->profilepic  // Store the relative path in the database
: null;

    
        $activity->save();

        // Extract the mentioned user's username from the message
        $mentionedUserUsername = $request->input('mentioned_user'); 

        if ($mentionedUserUsername) {
            // Find the mentioned user by their username
            $mentionedUser = User::where('username', $mentionedUserUsername)->first();
            if ($mentionedUser) {
                $companyName = $activity->payment->company_name;
                // Pass mentioning user (current user) and payment ID
                Mail::to($mentionedUser->email)->send(
                    new MentionedUserNotification(
                        $activity,
                        $mentionedUser,
                        Auth::user(), // The mentioning user
                        $companyName
                    )
                );
            }
        }
          // Load the user relationship to include the user data in the response
          $user = User::find(Auth::id());
          $activity->username = $user ? $user->username : 'Unknown'; // Assign username
      
          $activity->save();

        // Return a response to indicate the success of the operation
        return redirect()->route('payments.index')->with('success', 'Activity added successfully.');
    }

    public function showActivities($id)
    {
        $activities = PaymentsActivity::where('payments_id', $id)->orderBy('date', 'desc')->get();
        return response()->json(['activities' => $activities]);
    }

    public function getActivitiesByPayments($paymentsId)
    {
        // Fetch activities for a specific prospect along with user info
        $activities = PaymentsActivity::where('payments_id', $paymentsId)
            ->with('user') // Load the user data (username)
            ->get();
            $activities = $activities->map(function ($activity) {
                // Make sure profile picture is accessible publicly
                $activity->profile_pic = $activity->profile_pic
                    ? url('storage/' . $activity->profile_pic)  // Prefix with 'storage' to make it publicly accessible
                    : url('images/default-profile.png'); // Fallback to default profile picture
                $activity->username = $activity->user->username ?? 'Unknown'; // Add username
                return $activity;
            });

        return response()->json([
            'success' => true,
            'activities' => $activities,
        ]);
    }
    public function show($id)
{
    $activity = PaymentsActivity::find($id);
    if (!$activity) {
        return redirect()->route('payments.index')->with('error', 'Activity not found.');
    }

    // Optionally, you can pass the activity details to the view
    return view('activities.show', compact('activity'));
}

}
