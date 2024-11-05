<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task; 
use App\Models\User; 
use Carbon\Carbon; 

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();
               // Debugging: Log the request data
    \Log::info('Request Data:', $request->all());
         // Start a query for projects
         $query = Project::with(['tasks.assignedUser', 'tasks.assignedBy']); // Load related tasks and users
         
   
    
        // Apply filter if 'filter_status' is provided in the request
        if ($request->has('filter_status') && $request->filter_status != '') {
            $query->where('status', $request->filter_status);
        }

         // Search functionality
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where('name', 'like', "%{$searchTerm}%"); // Search by project name
    }

        // Apply sorting based on start date if provided
        if ($request->has('sort_start_date') && $request->sort_start_date != '') {
            if ($request->sort_start_date == 'recent') {
                $query->orderBy('start_date', 'desc'); // Sort by recent
            } elseif ($request->sort_start_date == 'oldest') {
                $query->orderBy('start_date', 'asc'); // Sort by oldest
            }
        }
       
      // Sorting by Due Date
    if ($request->has('sort_due_date')) {
        if ($request->input('sort_due_date') === 'more_time') {
            $query->orderBy('due_date', 'asc'); // Projects with later due dates appear first
        } elseif ($request->input('sort_due_date') === 'less_time') {
            $query->orderBy('due_date', 'desc'); // Projects with earlier due dates appear first
        }
    }

    // Apply sorting based on project name if provided
    if ($request->has('sort_project_name') && $request->sort_project_name != '') {
        if ($request->sort_project_name == 'a_to_z') {
            $query->orderBy('name', 'asc'); // Sort by project name A to Z
        } elseif ($request->sort_project_name == 'z_to_a') {
            $query->orderBy('name', 'desc'); // Sort by project name Z to A
        }
    }

  // Sorting by Time Left
  if ($request->has('sort_time_left')) {
    if ($request->input('sort_time_left') === 'time_left_asc') {
        $query->orderBy('time_left', 'asc'); // More Days Left
    } elseif ($request->input('sort_time_left') === 'time_left_desc') {
        $query->orderBy('time_left', 'desc'); // Less Days Left
    }
}



// Execute the query to fetch projects
$projects = $query->get();
    
        // Fetch all users
        $users = User::all();
    
        // Initialize $project to null if there are no projects
        $project = $projects->isEmpty() ? null : $projects;

    
        // Pass the initialized $project, $users, and $projects to the view
        return view('frontends.projects', compact('users', 'projects', 'project'));
    }
    
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'start_date' => 'nullable|date',
        'due_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'nullable|string|max:255',
    ]);

    $project = Project::create($validatedData);
    
    // Call calculateTimeLeft and save the time_left to the database
    $project->time_left = $this->calculateTimeLeft($project);
    $project->save();

    return redirect(url('/projects'))->with('success', 'Project created successfully.');
}



    public function destroy($id)
    {
        $project = Project::findOrFail($id); // Find the project or fail
        $project->delete(); // Delete the project

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'due_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|string',
    ]);

    $project = Project::findOrFail($id);
    $project->update($request->all());

    // Recalculate and save the updated time_left value
    $project->time_left = $this->calculateTimeLeft($project);
    $project->save();

    return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
}

    public function showTasks($projectId)
    {
        // Fetch tasks associated with the given project ID
        $tasks = Task::where('project_id', $projectId)->get();

        // Return a view with the tasks and project ID
        return view('frontends.tasks', compact('tasks', 'projectId'));
    }

    public function show($projectId)
    {
        $project = Project::with('tasks')->findOrFail($projectId);
        return view('frontends.projects', compact('project'));
    }
    // Calculate time_left for sorting
private function calculateTimeLeft($project)
{
    if (!$project->due_date) {
        return null;  // or handle as needed
    }
    $currentDate = Carbon::now();
    $dueDate = Carbon::parse($project->due_date);
    return $currentDate->diffInDays($dueDate, false);
}
}
