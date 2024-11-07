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
     
         // Get tasks assigned only to the logged-in user, including the user who assigned them
         $tasks = Task::with('assignedBy') // Eager load the assignedBy relationship
             ->where('assigned_to', $user->id)
             ->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
             ->get();
         // Get tasks assigned only to the logged-in user, including the user who assigned them
         $prospectTasks = ProspectTask::with('assignedBy') // Eager load the assignedBy relationship
             ->where('assigned_to', $user->id)
             ->select('id', 'name', 'assigned_by', 'start_date', 'due_date', 'priority')
             ->get();

         $paymentTasks = PaymentTask::with('assignedBy') // Eager load the assignedBy relationship
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
     
         // Debugging: Log the projects with tasks to verify filtering
         \Log::info("Projects for user {$user->email}:", $projects->toArray());
     
         return view('frontends.dashboard', compact('projects', 'payments', 'prospects', 'username', 'userEmail', 'user', 'tasks', 'prospectTasks'));
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

