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

use Illuminate\Http\Request;

class YourController extends Controller
{
    public function userDashboard($username)
{
    // Retrieve the user by username
    $user = User::where('username', $username)->firstOrFail();

    // Fetch tasks and sessions data as you did in the `dashboard` method
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

    $projects = Project::with(['tasks' => function($query) use ($user) {
        $query->where('assigned_to', $user->id);
    }])->get();

    $prospects = Prospect::with(['prospect_tasks' => function($query) use ($user) {
        $query->where('assigned_to', $user->id);
    }])->get();

    $payments = Payments::with(['payment_tasks' => function($query) use ($user) {
        $query->where('assigned_to', $user->id);
    }])->get();

    $taskSessions = TaskSession::where('user_id', $user->id)
        ->where('started_at', '>=', now()->subDay())
        ->with(['task', 'project'])
        ->get();

    $sessionsData = $taskSessions->groupBy('task_id')->map(function ($sessions) {
        $totalTimeSpentInSeconds = 0;

        foreach ($sessions as $session) {
            $startTime = $session->started_at instanceof \Carbon\Carbon ? $session->started_at : new \Carbon\Carbon($session->started_at);
            $endTime = $session->paused_at ? new \Carbon\Carbon($session->paused_at) : now();

            $totalTimeSpentInSeconds += abs($endTime->diffInSeconds($startTime));
        }

        $formattedTime = $totalTimeSpentInSeconds < 60 
            ? str_pad($totalTimeSpentInSeconds, 2, '0', STR_PAD_LEFT) . ' seconds' 
            : round($totalTimeSpentInSeconds / 60) . ' minutes';

        return [
            'task_name' => $sessions->first()->task->name,
            'project_name' => $sessions->first()->project->name,
            'time_spent' => $formattedTime,
        ];
    });

    return view('frontends.user-dashboard', compact('projects', 'payments', 'prospects', 'username', 'tasks', 'prospectTasks', 'paymentTasks', 'sessionsData'));
}
}
