<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Task;
use App\Models\ProspectTask;
use App\Models\Project;
use App\Models\Prospect;
use App\Models\Payments;
use App\Models\PaymentTask;
use App\Models\TaskSession;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;



class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

     public function dashboard()
     {
         $user = auth()->user();
         $userEmail = $user->email;
         $username = $user->username;
     
        // Modify the existing code to eager load related categories
$tasks = Task::with('assignedBy', 'project') // Eager load project
->where('assigned_to', $user->id)
->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
->get();

// Similarly, for prospect and payment tasks
$prospectTasks = ProspectTask::with('assignedBy', 'prospect') // Eager load prospect
->where('assigned_to', $user->id)
->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
->get();

$paymentTasks = PaymentTask::with('assignedBy', 'payment') // Eager load payment
->where('assigned_to', $user->id)
->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
->get();
     
         // Debugging: Log the tasks to see what's retrieved for this user
         \Log::info("Tasks for user {$user->email}:", $tasks->toArray());
     
         // Retrieve projects and include only tasks assigned to the logged-in user
         $projects = Project::with(['tasks' => function($query) use ($user) {
             $query->where('assigned_to', $user->id);
         }])->get();
         // Retrieve projects and include only tasks assigned to the logged-in user
         $prospects = Prospect::with(['prospect_tasks' => function($query) use ($user) {
             $query->where('assigned_to', $user->id);
         }])->get();
         // Retrieve projects and include only tasks assigned to the logged-in user
         $payments = Payments::with(['payment_tasks' => function($query) use ($user) {
             $query->where('assigned_to', $user->id);
         }])->get();

         // Fetch task sessions for the past 24 hours
   // Fetch task sessions for the past 24 hours
// Fetch task sessions for the past 24 hours
$taskSessions = TaskSession::where('user_id', $user->id)
    ->where('started_at', '>=', now()->subDay())
    ->with(['task', 'project'])
    ->get();

// Group task sessions by task ID and accumulate the time spent for each task
$sessionsData = $taskSessions->groupBy('task_id')->map(function ($sessions) {
    // Initialize total time spent in seconds
    $totalTimeSpentInSeconds = 0;

    foreach ($sessions as $session) {
        $startTime = $session->started_at instanceof \Carbon\Carbon ? $session->started_at : new \Carbon\Carbon($session->started_at);
        $endTime = $session->paused_at ? new \Carbon\Carbon($session->paused_at) : now();

        // Add the time spent on this session to the total time spent
        $totalTimeSpentInSeconds += $endTime->diffInSeconds($startTime);
    }

    // If the total time spent is less than 60 seconds, show in seconds
    if ($totalTimeSpentInSeconds < 60) {
        $formattedTime = str_pad($totalTimeSpentInSeconds, 2, '0', STR_PAD_LEFT) . ' seconds';
    } else {
        // Otherwise, show in minutes, rounding up to the nearest minute
        $totalTimeSpentInMinutes = round($totalTimeSpentInSeconds / 60); // Round to nearest minute
        $formattedTime = "{$totalTimeSpentInMinutes} minute" . ($totalTimeSpentInMinutes > 1 ? 's' : '');
    }

    // Return the task name, project name, and formatted total time spent
    return [
        'task_name' => $sessions->first()->task->name,
        'project_name' => $sessions->first()->project->name,
        'time_spent' => $formattedTime, // display formatted total time
    ];
});

         // Debugging: Log the projects with tasks to verify filtering
         \Log::info("Projects for user {$user->email}:", $projects->toArray());
   
        return view('frontends.dashboard', compact('projects', 'payments', 'prospects', 'username', 'userEmail', 'user', 'tasks', 'prospectTasks', 'paymentTasks', 'sessionsData'));

     }
     



    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
    $user = $request->user();
    $data = $request->validated();

    if ($request->hasFile('profilepic')) {
        // Delete old profile picture if exists
        if ($user->profilepic) {
            Storage::delete($user->profilepic);
        }

        // Store new profile picture
        $data['profilepic'] = $request->file('profilepic')->store('profilepics', 'public');
    }
  

    $user->fill($data);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');


    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function index()
{
    $user = auth()->user(); // or any other logic to get user
    return view('frontends.layouts.header', compact('user'));
}

public function getUsernames(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'query' => 'required|string|min:1',
    ]);

    // Get the search query from the request
    $searchQuery = $request->input('query');

    // Retrieve usernames that start with the search query
    $usernames = User::where('username', 'LIKE', "{$searchQuery}%")
        ->pluck('username'); // Retrieve usernames only

    return response()->json($usernames);
}



}

