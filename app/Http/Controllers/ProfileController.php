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
use DateTimeZone;
use DateTime;
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
        $hourlySessionsData = [];

        // Convert time to Nepali Time (NPT)
        $nepaliTimeZone = new DateTimeZone('Asia/Kathmandu');

        // Get current time in Nepali Time
        $currentNepaliTime = Carbon::now($nepaliTimeZone);

        // Log the current Nepali time
\Log::info('Current Nepali Time:', ['time' => $currentNepaliTime->toDateTimeString()]);

        foreach (range(0, 23) as $hour) {
            $startInterval = Carbon::now()->startOfDay()->addHours($hour);
            $endInterval = $startInterval->copy()->addHour();

            // Adjust to Nepali Time
            $startInterval = $startInterval->setTimezone($nepaliTimeZone);
            $endInterval = $endInterval->setTimezone($nepaliTimeZone);

            $hourlyData = $taskSessions->filter(function ($session) use ($startInterval, $endInterval) {
                return $session->started_at->between($startInterval, $endInterval);
            })->groupBy('task_id')->map(function ($sessions) use ($startInterval, $endInterval, $nepaliTimeZone) {
                $totalTimeSpentInSeconds = 0;

                foreach ($sessions as $session) {
                    $startTime = max($session->started_at, $startInterval);
                    $endTime = min($session->paused_at ?? now(), $endInterval);

                    // Adjust to Nepali Time
                    $startTime = $startTime->setTimezone($nepaliTimeZone);
                    $endTime = $endTime->setTimezone($nepaliTimeZone);

                    // Calculate the difference between start and end time
                    $start = new DateTime($startTime);
                    $end = new DateTime($endTime);
                    $interval = $start->diff($end);
                    $totalTimeSpentInSeconds += $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
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
            'hourlySessionsData'
        ));
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
