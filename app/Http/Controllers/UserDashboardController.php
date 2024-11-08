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

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function userDashboard($username)
  
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
        $projects = Project::with(['tasks' => function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
        }])->get();
        // Retrieve projects and include only tasks assigned to the logged-in user
        $prospects = Prospect::with(['prospect_tasks' => function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
        }])->get();
        // Retrieve projects and include only tasks assigned to the logged-in user
        $payments = Payments::with(['payment_tasks' => function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
        }])->get();

    

        // Fetch task sessions for the past 24 hours
        $taskSessions = TaskSession::where('user_id', $user->id)
            ->where('started_at', '>=', now()->subDay())
            ->with(['task', 'project'])
            ->get();
        
        // Initialize hourly sessions data array
        $hourlySessionsData = [];
        
        foreach (range(0, 23) as $hour) {
            $startInterval = Carbon::now()->startOfDay()->addHours($hour);
            $endInterval = $startInterval->copy()->addHour();
        
            $hourlyData = $taskSessions->filter(function ($session) use ($startInterval, $endInterval) {
                return $session->started_at->between($startInterval, $endInterval);
            })->groupBy('task_id')->map(function ($sessions) use ($startInterval, $endInterval) {
                $totalTimeSpentInSeconds = 0;
        
                foreach ($sessions as $session) {
                    $startTime = max($session->started_at, $startInterval);
                    $endTime = min($session->paused_at ?? now(), $endInterval);
                    $totalTimeSpentInSeconds += $endTime->diffInSeconds($startTime);
                }
        
                $totalTimeSpentInMinutes = round($totalTimeSpentInSeconds / 60);
                $formattedTime = "{$totalTimeSpentInMinutes} minute" . ($totalTimeSpentInMinutes > 1 ? 's' : '');
        
                return [
                    'task_name' => $sessions->first()->task->name,
                    'project_name' => $sessions->first()->project->name,
                    'time_spent' => $formattedTime,
                ];
            });
        
            $intervalLabel = $startInterval->format('g A') . ' - ' . $endInterval->format('g A');
            $hourlySessionsData[$intervalLabel] = $hourlyData;
        }
        
        return view('frontends.user-dashboard', compact(
            'projects', 'payments', 'prospects', 'username', 'userEmail', 'user', 'tasks', 'prospectTasks', 'paymentTasks', 'hourlySessionsData'
        ));
    }
}
