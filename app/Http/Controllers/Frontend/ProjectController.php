<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();
        $filterCount = 0;

        // Start a query for projects
        $query = Project::with(['tasks.assignedUser', 'tasks.assignedBy']); // Load related tasks and users


        // Filtering by Start Date
        if ($request->filled('start_date')) {
            $filterCount++;
            switch ($request->start_date) {
                case 'recent':
                    $query->orderBy('start_date', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('start_date', 'asc');
                    break;
                case 'date-range':
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('start_date', [$request->from_date, $request->to_date]);
                        $filterCount++;
                    }
                    break;
            }
        }

        // Filtering by Due Date
        if ($request->filled('due_date')) {
            $filterCount++;
            switch ($request->due_date) {
                case 'More-Time':
                    $query->orderBy('due_date', 'desc');
                    break;
                case 'Less-Time':
                    $query->orderBy('due_date', 'asc');
                    break;
                case 'date-range':
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('due_date', [$request->from_date, $request->to_date]);
                        $filterCount++;
                    }
                    break;
            }
        }
        // Filtering by Status
        if ($request->filled('sort_status')) {
            $query->where('status', $request->sort_status);
            $filterCount++;
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%"); // Search by project name
        }

        // Execute the query to fetch projects
        $projects = $query->get();

        // Fetch all users
        $users = User::all();

        // Initialize $project to null if there are no projects
        $project = $projects->isEmpty() ? null : $projects;


        // Pass the initialized $project, $users, and $projects to the view
        return view('frontends.projects', compact('users', 'projects', 'project', 'filterCount'));
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
        Log::debug('Request Data:', $request->all());  // Log all request data
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'sub-status' => 'nullable|string|max:255', // Validate sub-status if it's being updated
        ]);
    
        $project = Project::findOrFail($id);
    
        // Update fields manually
        $project->name = $request->input('name');
        $project->start_date = $request->input('start_date');
        $project->due_date = $request->input('due_date');
        $project->status = $request->input('status');
        $project->sub_status = $request->input('sub-status'); // Manually update sub-status
    
        // Save updated project
        $project->save();
    
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

    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'taskId' => 'required|integer|exists:projects,id',
            'status' => 'required|string|max:255'
        ]);
    
        // Find the prospect by ID and update the status
        $project = Project::findOrFail($validatedData['taskId']);
        $project->status = $validatedData['status'];
        $project->save();
    
        // Send notifications to specified emails
        // $emails = ['gaurav@webtech.com.np'];
        // Notification::route('mail', $emails)->notify(new ProjectStatusUpdated($project, $validatedData['status']));
    
        // Return a success response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }


    public function showdetails($id)
    {
        // Fetch the project by ID
        $project = Project::with(['tasks' => function ($query) {
            // Eager load tasks for all statuses
            $query->orderBy('status');
        }])->findOrFail($id);
    
        // Fetch all users for task assignment
        $users = User::all();
    
        // Separate tasks by status
        $todoTasks = $project->tasks->where('status', null); // For "To Do" tasks (status = null)
        $inProgressTasks = $project->tasks->where('status', 'In Progress'); // For "In Progress" tasks
        $qaTasks = $project->tasks->where('status', 'QA'); // For "QA" tasks
        $completedTasks = $project->tasks->where('status', 'Completed'); // For "Completed" tasks
    
        // Pass the data to the view
        return view('frontends.project-details-page', compact('project', 'users', 'todoTasks', 'inProgressTasks', 'qaTasks', 'completedTasks'));
    }
    

    
}
