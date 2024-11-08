<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\ProspectTask;
use App\Models\Project;
use App\Models\Prospect;
use App\Models\Payments;
use App\Models\PaymentTask;
use App\Models\TaskSession;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;

class ProfileController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();
        $userEmail = $user->email;
        $username = $user->username;

        // Load tasks, prospect tasks, and payment tasks assigned to the user
        $tasks = Task::with('assignedBy', 'project')
            ->where('assigned_to', $user->id)
            ->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
            ->get();

        $prospectTasks = ProspectTask::with('assignedBy', 'prospect')
            ->where('assigned_to', $user->id)
            ->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
            ->get();

        $paymentTasks = PaymentTask::with('assignedBy', 'payment')
            ->where('assigned_to', $user->id)
            ->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
            ->get();

        // Retrieve projects, prospects, and payments, including only tasks assigned to the user
        $projects = Project::with(['tasks' => function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
        }])->get();

        $prospects = Prospect::with(['prospect_tasks' => function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
        }])->get();

        $payments = Payments::with(['payment_tasks' => function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
        }])->get();

        // Fetch task sessions for the past 24 hours
        $taskSessions = TaskSession::where('user_id', $user->id)
            ->where('started_at', '>=', now()->subDay())
            ->with(['task', 'project'])
            ->get();

        // Initialize hourly sessions data array
       // Initialize hourly sessions data array
$hourlySessionsData = [];
// Initialize a summary array to store total time spent on each task
$taskSummaryData = [];

// Nepali timezone
$nepaliTimeZone = new DateTimeZone('Asia/Kathmandu');

// Iterate over each hour from midnight to 11 PM
foreach (range(0, 23) as $hour) {
    // Define start and end of the hourly interval in Nepali timezone
    $startInterval = Carbon::now()->startOfDay()->addHours($hour)->setTimezone($nepaliTimeZone);
    $endInterval = $startInterval->copy()->addHour()->setTimezone($nepaliTimeZone);

    // Filter task sessions by this time range
    $hourlyData = $taskSessions->filter(function ($session) use ($startInterval, $endInterval, $nepaliTimeZone) {
        $sessionStart = $session->started_at->copy()->setTimezone($nepaliTimeZone);
        $sessionEnd = ($session->paused_at ?? now())->copy()->setTimezone($nepaliTimeZone);

        return $sessionStart->between($startInterval, $endInterval) || $sessionEnd->between($startInterval, $endInterval);
    })->groupBy('task_id')->map(function ($sessions) use ($startInterval, $endInterval, $nepaliTimeZone) {
        $totalTimeSpentInSeconds = 0;

        foreach ($sessions as $session) {
            $startTime = max($session->started_at->copy()->setTimezone($nepaliTimeZone), $startInterval);
            $endTime = min(($session->paused_at ?? now())->copy()->setTimezone($nepaliTimeZone), $endInterval);

            $totalTimeSpentInSeconds += $startTime->diffInSeconds($endTime);
        }

        $formattedTime = $this->formatDuration($totalTimeSpentInSeconds);

        return [
            'task_name' => $sessions->first()->task->name,
            'project_name' => $sessions->first()->project->name,
            'time_spent' => $formattedTime,
        ];
    });

    $intervalLabel = $startInterval->format('g A') . ' - ' . $endInterval->format('g A');
    $hourlySessionsData[$intervalLabel] = $hourlyData;
}

// Sort intervals to ensure they start from 12 AM and end at 11 PM
ksort($hourlySessionsData);


// Iterate through the task sessions and sum the time spent on each task
foreach ($taskSessions as $session) {
    // Calculate the time spent for each session
    $sessionStart = $session->started_at;
    $sessionEnd = $session->paused_at ?? now();
    $timeSpentInSeconds = $sessionStart->diffInSeconds($sessionEnd);

    // Add to the task's total time spent
    if (!isset($taskSummaryData[$session->task_id])) {
        $taskSummaryData[$session->task_id] = [
            'task_name' => $session->task->name,
            'total_time_spent' => 0,
        ];
    }

    $taskSummaryData[$session->task_id]['total_time_spent'] += $timeSpentInSeconds;
}

// Format the total time spent for each task in hours and minutes
foreach ($taskSummaryData as &$taskData) {
    $taskData['formatted_time'] = $this->formatDuration($taskData['total_time_spent']);
}

return view('frontends.dashboard', compact(
    'projects',
    'payments',
    'prospects',
    'username',
    'userEmail',
    'user',
    'tasks',
    'prospectTasks',
    'paymentTasks',
    'hourlySessionsData',
    'taskSummaryData' 
));
    }

    /**
     * Helper function to format duration from seconds to human-readable format.
     */
    protected function formatDuration($totalTimeInSeconds)
    {
        if ($totalTimeInSeconds < 60) {
            return "{$totalTimeInSeconds} seconds";
        } elseif ($totalTimeInSeconds < 3600) {
            $minutes = floor($totalTimeInSeconds / 60);
            $seconds = str_pad($totalTimeInSeconds % 60, 2, '0', STR_PAD_LEFT);
            return "{$minutes} minute" . ($minutes > 1 ? 's' : '') . " {$seconds} seconds";
        } else {
            $hours = floor($totalTimeInSeconds / 3600);
            $minutes = floor(($totalTimeInSeconds % 3600) / 60);
            $seconds = str_pad($totalTimeInSeconds % 60, 2, '0', STR_PAD_LEFT);
            return "{$hours} hour" . ($hours > 1 ? 's' : '') . " {$minutes} minute" . ($minutes > 1 ? 's' : '') . " {$seconds} seconds";
        }
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
