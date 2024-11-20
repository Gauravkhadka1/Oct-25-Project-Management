<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\MentionedUserNotificationProspect; // Create the mail class for notifications
use App\Mail\ContactFormMail;
use App\Models\Activity; //
use App\Models\User;
use App\Models\Reply;
use App\Models\like;



class ActivityController extends Controller
{
    public function store(Request $request)

    {
        $activity = new Activity();
        $activity->prospect_id = $request->input('prospect_id');
        $activity->details = $request->input('message');
        $activity->user_id = Auth::id();  // Store the authenticated user's ID
        $activity->save();
    

        // Extract the mentioned user's username from the message
        $mentionedUserUsername = $request->input('mentioned_user'); 

        if ($mentionedUserUsername) {
            // Find the mentioned user by their username
            $mentionedUser = User::where('username', $mentionedUserUsername)->first();
            if ($mentionedUser) {
                $companyName = $activity->prospect->company_name;
                // Pass mentioning user (current user) and payment ID
                Mail::to($mentionedUser->email)->send(
                    new MentionedUserNotificationProspect(
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
    
    return redirect()->route('prospects.index')->with('success', 'Activity added successfully.');
    }
    
    public function showActivities($id)
    {
        $activities = Activity::where('prospect_id', $id)->orderBy('date', 'desc')->get();
        return response()->json(['activities' => $activities]);
    }

    public function getActivitiesByProspect($prospectId)
{
    // Fetch activities for a specific prospect along with user info
    $activities = Activity::where('prospect_id', $prospectId)
                    ->with('user') // Load the user data (username)
                    ->get();

    return response()->json([
        'success' => true,
        'activities' => $activities,
    ]);
}
}
