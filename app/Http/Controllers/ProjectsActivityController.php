<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\MentionedUserNotification; // Create the mail class for notifications
use App\Mail\MentionedUserNotificationProject;
use App\Models\ProjectsActivity;
use App\Models\User;
use App\Models\Task;

class ProjectsActivityController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comments' => 'required|string|max:255',
        ]);
        // Retrieve the task to get project_id
    $task = Task::findOrFail($request->input('task_id'));
        // Create a new activity record
        $projectsActivity = new ProjectsActivity();
        $projectsActivity->task_id = $request->input('task_id');
        $projectsActivity->project_id = $task->project_id; // Save the project_id
        $projectsActivity->details = $request->input('comments'); // Store the message with the mention
        $projectsActivity->user_id = Auth::id();  // Store the authenticated user's ID
        $user = Auth::user();
          // Store the relative path to profile picture
        $projectsActivity->profile_pic = $user && $user->profilepic
        ? 'profile_pictures/' . $user->profilepic  // Store the relative path in the database
        : null;

        $projectsActivity->save();

        // Extract the mentioned user's username from the message
        $mentionedUserUsername = $request->input('mentioned_user'); 

        if ($mentionedUserUsername) {
            // Find the mentioned user by their username
            $mentionedUser = User::where('username', $mentionedUserUsername)->first();
            if ($mentionedUser) {
                $projectsActivity->load('task'); 
               
                // Pass mentioning user (current user) and payment ID
                Mail::to($mentionedUser->email)->send(
                    new MentionedUserNotificationProject(
                        $projectsActivity,
                        $mentionedUser,
                        Auth::user(), 
                        $task->project->name ?? 'Unknown Project', // Pass project name
                        $request->input('comments') // Pass the comment from the request
                    )
                );
            }
        }
          // Load the user relationship to include the user data in the response
          $user = User::find(Auth::id());
          $projectsActivity->username = $user ? $user->username : 'Unknown'; // Assign username
      
          $projectsActivity->save();

        // Return a response to indicate the success of the operation
        return redirect()->route('payments.index')->with('success', 'Activity added successfully.');
    }

    public function showActivities($id)
    {
        $activities = ProjectsActivity::where('payments_id', $id)->orderBy('date', 'desc')->get();
        return response()->json(['activities' => $activities]);
    }

    public function getActivitiesByProjecttask($taskId)
{
    // Fetch activities for a specific task along with user info
    $activities = ProjectsActivity::where('task_id', $taskId)
        ->with('user') // Ensure the user relationship is loaded
        
        ->get();
        $activities = $activities->map(function ($activity) {
            // Ensure profile_pic is correctly formatted
            $activity->profile_pic = filter_var($activity->profile_pic, FILTER_VALIDATE_URL)
                ? $activity->profile_pic // If already a valid URL, use it as is
                : ($activity->profile_pic 
                    ? ( $activity->profile_pic) // Otherwise, prepend 'storage'
                    : ('images/default-profile.png')); // Fallback to default profile picture
    
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
    $activity = ProjectsActivity::find($id);
    if (!$activity) {
        return redirect()->route('payments.index')->with('error', 'Activity not found.');
    }

    // Optionally, you can pass the activity details to the view
    return view('activities.show', compact('activity'));
}

}
