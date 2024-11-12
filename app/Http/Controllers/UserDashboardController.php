<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;

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
use DateTimeZone;
use Illuminate\Http\Request;

use App\Models\PaymentTaskSession;
use App\Models\ProspectTaskSession;

use DateTime;

class UserDashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function userdashboard(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail(); 
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
        $nepaliTimeZone = new DateTimeZone('Asia/Kathmandu');
    
        // Convert selected date to the start of the day in Nepali timezone
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
    
        // Combine all task sessions (projects, prospects, payments)
        $allSessions = $taskSessions->merge($prospectTaskSessions)->merge($paymentTaskSessions);
    
        // Initialize hourly sessions data array
        $hourlySessionsData = [];
    
        // Iterate over each hour of the day
        foreach (range(0, 23) as $hour) {
            // Define start and end of the hourly interval in Nepali timezone
            $startInterval = $nepaliStartOfDay->copy()->addHours($hour);
            $endInterval = $startInterval->copy()->addHour();
    
            // Generate the interval label
            $intervalLabel = $startInterval->format('g A') . ' - ' . $endInterval->format('g A');
    
            // Filter all sessions by this time range
            $hourlyData = $allSessions->filter(function ($session) use ($startInterval, $endInterval, $nepaliTimeZone) {
                $sessionStart = $session->started_at->copy()->setTimezone($nepaliTimeZone);
                $sessionEnd = ($session->paused_at ?? now())->copy()->setTimezone($nepaliTimeZone);
    
                return $sessionStart->between($startInterval, $endInterval) || $sessionEnd->between($startInterval, $endInterval);
            })->groupBy(function ($session) {
                // Group by task ID, taking into account different task types (Prospect, Payment, Project)
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
    
            // Only add this interval if it has tasks
            if (!$hourlyData->isEmpty()) {
                $hourlySessionsData[$intervalLabel] = $hourlyData;
            }
        }
    
        // Prepare summary data for the total time spent on each task
        $taskSummaryData = [];
        $totalTimeSpentAcrossTasks = 0;
        foreach ($hourlySessionsData as $interval => $tasks) {
            foreach ($tasks as $taskId => $taskData) {
                if (!isset($taskSummaryData[$taskId])) {
                    $taskSummaryData[$taskId] = [
                        'task_name' => $taskData['task_name'],
                        'project_name' => $taskData['project_name'],
                        'total_time_spent' => 0,
                    ];
                }
    
                // Convert time spent to seconds for each interval
                $timeSpentInSeconds = $this->parseDurationToSeconds($taskData['time_spent']);
    
                // Accumulate total time spent on the task
                $taskSummaryData[$taskId]['total_time_spent'] += $timeSpentInSeconds;
    
                // Add to the overall total time spent on all tasks
                $totalTimeSpentAcrossTasks += $timeSpentInSeconds;
            }
        }
    
        // Format total time spent back to HH:MM:SS for display
        foreach ($taskSummaryData as &$summary) {
            $summary['total_time_spent'] = $this->formatDuration($summary['total_time_spent']);
        }
    
        // Prepare intervals for the view (you can adjust this as needed)
        $defaultIntervals = ['10 AM - 11 AM', '11 AM - 12 PM', '12 PM - 1 PM', '1 PM - 2 PM', '2 PM - 3 PM', '3 PM - 4 PM', '4 PM - 5 PM', '5 PM - 6 PM'];
        if (!$hourlyData->isEmpty() || in_array($intervalLabel, $defaultIntervals)) {
            $hourlySessionsData[$intervalLabel] = $hourlyData;
        }
    
        // Format total time spent for display
        $totalTimeSpentAcrossTasksFormatted = $this->formatDuration($totalTimeSpentAcrossTasks);
// Pass the updated data to the view
return view('frontends.user-dashboard', compact(
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
'selectedDate', // Pass the selected date to the view
));

    }
    protected function parseDurationToSeconds($duration)
{
    // Check if duration has only seconds
    if (is_numeric($duration)) {
        return (int)$duration;
    }
    
    // Ensure the duration is in HH:MM:SS format by padding missing components
    $parts = explode(':', $duration);
    $parts = array_pad($parts, -3, '0'); // Pad on the left for missing hours or minutes

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


}