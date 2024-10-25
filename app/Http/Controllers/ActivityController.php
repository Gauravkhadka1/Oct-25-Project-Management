<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\ContactFormMail;
use App\Models\Activity; //
use App\Models\User;

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






}
