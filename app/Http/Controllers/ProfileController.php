<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;
use App\Models\Project;
use App\Models\Prospect;
use App\Models\Payments;
use App\Models\TaskSession;
use App\Models\PaymentTaskSession;
use App\Models\ProspectTaskSession;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;

class ProfileController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $userEmail = $user->email;
        $username = $user->username;
    
        // Get the selected date from the request or default to today's date
        $selectedDate = $request->input('date', Carbon::now('Asia/Kathmandu')->toDateString());
    
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
    
        // Define the Nepali timezone
        // Define the Nepali timezone and calculate the UTC start and end of the selected date
    $nepaliTimeZone = new DateTimeZone('Asia/Kathmandu');
    $nepaliStartOfDay = Carbon::parse($selectedDate, $nepaliTimeZone)->startOfDay();
    $utcStartOfDay = $nepaliStartOfDay->copy()->setTimezone('UTC');
    $utcEndOfDay = $nepaliStartOfDay->copy()->endOfDay()->setTimezone('UTC');

    // Fetch sessions for all task categories within the selected date
    $taskSessions = TaskSession::where('user_id', $user->id)
        ->whereBetween('started_at', [$utcStartOfDay, $utcEndOfDay])
        ->with(['task', 'project'])
        ->get();

    $prospectTaskSessions = ProspectTaskSession::where('user_id', $user->id)
        ->whereBetween('started_at', [$utcStartOfDay, $utcEndOfDay])
        ->with(['prospectTask', 'prospect'])
        ->get();

    $paymentTaskSessions = PaymentTaskSession::where('user_id', $user->id)
        ->whereBetween('started_at', [$utcStartOfDay, $utcEndOfDay])
        ->with(['paymentTask', 'payment'])
        ->get();

    // Combine all task sessions
    $allSessions = $taskSessions->merge($prospectTaskSessions)->merge($paymentTaskSessions);

    // Initialize hourly sessions data array and task summary data
    $hourlySessionsData = [];
    $taskSummaryData = [];
    $totalTimeSpentAcrossTasks = 0;

    // Iterate over each hour of the day
    foreach (range(0, 23) as $hour) {
        $startInterval = $nepaliStartOfDay->copy()->addHours($hour);
        $endInterval = $startInterval->copy()->addHour();

        // Generate the interval label
        $intervalLabel = $startInterval->format('g A') . ' - ' . $endInterval->format('g A');

        // Filter sessions by this time range and calculate time spent
        $hourlyData = $allSessions->filter(function ($session) use ($startInterval, $endInterval, $nepaliTimeZone) {
            $sessionStart = $session->started_at->copy()->setTimezone($nepaliTimeZone);
            $sessionEnd = ($session->paused_at ?? now())->copy()->setTimezone($nepaliTimeZone);
            return $sessionStart->between($startInterval, $endInterval) || $sessionEnd->between($startInterval, $endInterval);
        })->groupBy(function ($session) {
            return $session->task_id ?? ($session->prospect_task_id ?? $session->payment_task_id);
        })->map(function ($sessions) use ($startInterval, $endInterval) {
            $totalTimeSpentInSeconds = 0;
            foreach ($sessions as $session) {
                $startTime = max($session->started_at->copy()->setTimezone('Asia/Kathmandu'), $startInterval);
                $endTime = min(($session->paused_at ?? now())->copy()->setTimezone('Asia/Kathmandu'), $endInterval);
                $totalTimeSpentInSeconds += $startTime->diffInSeconds($endTime);
            }
            $formattedTime = $this->formatDuration($totalTimeSpentInSeconds);
            return [
                'task_name' => $sessions->first()->task->name ?? $sessions->first()->prospectTask->name ?? $sessions->first()->paymentTask->name,
                'project_name' => $sessions->first()->project->name ?? $sessions->first()->prospect->company_name ?? $sessions->first()->payment->company_name,
                'time_spent' => $formattedTime,
            ];
        });

        if (!$hourlyData->isEmpty()) {
            $hourlySessionsData[$intervalLabel] = $hourlyData;
        }
    }

    // Calculate total time spent per task and overall total
    foreach ($hourlySessionsData as $interval => $tasks) {
        foreach ($tasks as $taskId => $taskData) {
            if (!isset($taskSummaryData[$taskId])) {
                $taskSummaryData[$taskId] = [
                    'task_name' => $taskData['task_name'],
                    'project_name' => $taskData['project_name'],
                    'total_time_spent' => 0,
                ];
            }
            $timeSpentInSeconds = $this->parseDurationToSeconds($taskData['time_spent']);
            $taskSummaryData[$taskId]['total_time_spent'] += $timeSpentInSeconds;
            $totalTimeSpentAcrossTasks += $timeSpentInSeconds;
        }
    }

    // Format total time spent back to HH:MM:SS for display
    foreach ($taskSummaryData as &$summary) {
        $summary['total_time_spent'] = $this->formatDuration($summary['total_time_spent']);
    }
    $totalTimeSpentAcrossTasksFormatted = $this->formatDuration($totalTimeSpentAcrossTasks);

    // Debugging output to check data structure (remove these lines after testing)
    // dd($taskSummaryData, $totalTimeSpentAcrossTasksFormatted);

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
        'taskSummaryData',
        'totalTimeSpentAcrossTasksFormatted',
        'selectedDate'
    ));
}

/**
 * Convert a duration string (HH:MM:SS) to seconds.
 */
/**
 * Convert a duration string (HH:MM:SS) to seconds.
 */
protected function parseDurationToSeconds($duration)
{
    // Ensure the duration is in HH:MM:SS format
    $parts = explode(':', $duration);
    $parts = array_pad($parts, -3, '0'); // Pad with 0 on the left for missing components

    list($hours, $minutes, $seconds) = array_map('intval', $parts);
    return ($hours * 3600) + ($minutes * 60) + $seconds;
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
