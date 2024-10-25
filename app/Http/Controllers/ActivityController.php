<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail; // Import the Mail facade
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
        $activity->details = $request->input('details');
        $activity->user_id = Auth::id();  // Store the authenticated user's ID
        $activity->save();
    
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


public function likeActivity($activityId)
{
    try {
        $activity = Activity::findOrFail($activityId);
        $activity->likes += 1;
        $activity->save();

        // Assuming you want to return an updated HTML fragment
        return view('partials.activity-likes', ['activity' => $activity]);
    } catch (Exception $e) {
        return response()->view('errors.custom-error', ['message' => 'Error liking activity'], 500);
    }
}




public function replyToActivity(Request $request, $activityId)
{
    try {
        $activity = Activity::findOrFail($activityId);

        $reply = new Reply();
        $reply->activity_id = $activityId;
        $reply->user_id = auth()->id();
        $reply->reply = $request->input('reply');
        $reply->save();

        // Assuming you want to return an updated HTML fragment for replies
        return view('partials.activity-replies', ['activity' => $activity]);
    } catch (Exception $e) {
        return response()->view('errors.custom-error', ['message' => 'Error replying to activity'], 500);
    }
}




}
