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
use Carbon\Carbon;
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
// Define the hourly range (10 AM to 6 PM)


// Define the hourly range in Nepal time (10 AM to 6 PM)


// Fetch all task sessions within the last 24 hours in UTC
$taskSessions = TaskSession::where('user_id', $user->id)
    ->where('started_at', '>=', now()->subDay())
    ->with(['task', 'project'])
    ->get();

// Initialize an array to hold session data by hour
$sessionsData = [];

for ($hour = 0; $hour < 24; $hour++) {
    $sessionsData[$hour] = [];

    // Filter and group sessions within this hour in Nepal time
    $filteredSessions = $taskSessions->filter(function ($session) use ($hour) {
        $start = Carbon::parse($session->started_at)->setTimezone('Asia/Kathmandu');
        $end = $session->paused_at ? Carbon::parse($session->paused_at)->setTimezone('Asia/Kathmandu') : now('Asia/Kathmandu');

        // Check if the session overlaps with the current hour interval
        return ($start->hour <= $hour && $end->hour >= $hour);
    });

    // Group the filtered sessions by task and calculate time spent within this hour
    $sessionsData[$hour] = $filteredSessions->groupBy('task_id')->map(function ($sessions) use ($hour) {
        $totalTimeSpentInSeconds = 0;

        foreach ($sessions as $session) {
            $startTime = Carbon::parse($session->started_at)->setTimezone('Asia/Kathmandu');
            $endTime = $session->paused_at ? Carbon::parse($session->paused_at)->setTimezone('Asia/Kathmandu') : now('Asia/Kathmandu');

            // Calculate overlapping time within this specific hour interval
            if ($startTime->hour == $hour) {
                $intervalStart = $startTime;
                $intervalEnd = $endTime->hour == $hour ? $endTime : $startTime->copy()->endOfHour();
            } elseif ($endTime->hour == $hour) {
                $intervalStart = $startTime->copy()->startOfHour();
                $intervalEnd = $endTime;
            } else {
                $intervalStart = $startTime->copy()->startOfHour();
                $intervalEnd = $startTime->copy()->endOfHour();
            }

            // Accumulate time spent in this hour interval
            $totalTimeSpentInSeconds += $intervalEnd->diffInSeconds($intervalStart);
        }

        $totalTimeSpentInMinutes = round($totalTimeSpentInSeconds / 60);
        $formattedTime = "{$totalTimeSpentInMinutes} minute" . ($totalTimeSpentInMinutes > 1 ? 's' : '');

        return [
            'task_name' => $sessions->first()->task->name,
            'project_name' => $sessions->first()->project->name,
            'time_spent' => $formattedTime,
        ];
    });
}

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

