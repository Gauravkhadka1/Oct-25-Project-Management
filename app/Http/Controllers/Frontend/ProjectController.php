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
    $query = Project::with(['tasks.assignedTo', 'tasks.assignedBy'])->orderBy('created_at', 'desc');
    $filterCount = 0;

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
        $query->where('name', 'like', "%{$searchTerm}%");
    }

    // Execute the query to fetch projects
    $projects = $query->get()->map(function ($project) {
        $project->time_left = $this->calculateTimeLeft($project);
        return $project;
    });

    $users = User::all();
    $project = $projects->isEmpty() ? null : $projects;

    // Fetch and sort counts
    $newProjects = Project::where('status', 'new')->orderBy('created_at', 'desc')->count();
    $designProjects = Project::where('status', 'design')->count();
    $developmentProjects = Project::where('status', 'development')->count();
    $contentfillupProjects = Project::where('status', 'content-fillup')->count();
    $completedProjects = Project::where('status', 'completed')->count();

    // Return the view
    return view('frontends.projects', compact(
        'users',
        'projects',
        'project',
        'filterCount',
        'newProjects',
        'designProjects',
        'developmentProjects',
        'contentfillupProjects',
        'completedProjects'
    ));
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

    
    public function calculateTimeLeft($project)
{
    if (!$project->due_date) {
        return 'No due date';
    }

    // Parse the due date and set the timezone to Nepali Time (NPT)
    $dueDate = Carbon::parse($project->due_date)->setTimezone('Asia/Kathmandu')->startOfDay();
    
    // Get the current time in Nepali Time (NPT) and set it to midnight (start of the day)
    $now = Carbon::now('Asia/Kathmandu')->startOfDay();

    // Check if the project is overdue
    if ($dueDate->isPast()) {
        // Ensure the number of overdue days is an integer
        $daysOverdue = $dueDate->diffInDays($now);
        return "Overdue by {$daysOverdue} day" . ($daysOverdue > 1 ? 's' : '');
    }

    // Calculate the time left in full days
    $daysLeft = $now->diffInDays($dueDate);

    // If there are no days left, indicate it's due today
    if ($daysLeft == 0) {
        return 'Due today';
    } else {
        return "{$daysLeft} day" . ($daysLeft > 1 ? 's' : '') . ' left'; // Format as "X day(s) left"
    }
}


    
    

    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'taskId' => 'required|integer|exists:projects,id',
            'status' => 'required|string|max:255'
        ]);

        // Find the project by ID and update the status
        $project = Project::findOrFail($validatedData['taskId']);
        $project->status = $validatedData['status'];
        $project->save();

        // Return a success response
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function showdetails($id)
    {
        // Set the timezone to Nepali time
        $nepaliTimezone = 'Asia/Kathmandu';
    
        // Fetch the project and related tasks with assigned user details
        $project = Project::with(['tasks.assignedTo' => function ($query) {
            $query->select('id', 'username', 'profilepic'); // Fetch only necessary columns
        }])->findOrFail($id);
    
        // Fetch all users for task assignment
        $users = User::select('id', 'username')->get(); // Include only needed fields
    
        // Separate tasks by status
        $todoTasks = $project->tasks->filter(fn($task) => $task->status === null || $task->status === 'TO DO');
        $inProgressTasks = $project->tasks->where('status', 'IN PROGRESS');
        $qaTasks = $project->tasks->where('status', 'QA');
        $completedTasks = $project->tasks->where('status', 'COMPLETED');
    
        // Calculate remaining time for each task
        foreach ($project->tasks as $task) {
            if ($task->due_date) {
                $now = Carbon::now($nepaliTimezone); // Current time in Nepali timezone
                $dueDate = Carbon::parse($task->due_date, $nepaliTimezone); // Due date in Nepali timezone
    
                if ($now->lessThanOrEqualTo($dueDate)) {
                    // Calculate remaining days and hours
                    $totalHours = $now->diffInHours($dueDate, false);
                    $task->remaining_days = floor($totalHours / 24);
                    $task->remaining_hours = $totalHours % 24;
                } else {
                    // Task is overdue
                    $overdueHours = $now->diffInHours($dueDate, false);
                    $task->overdue_days = floor(abs($overdueHours) / 24);
                    $task->overdue_hours = abs($overdueHours) % 24;
                }
            } else {
                $task->remaining_days = null;
                $task->remaining_hours = null;
                $task->overdue_days = null;
                $task->overdue_hours = null;
            }
        }
    
        // Pass the data to the view
        return view('frontends.project-details-page', compact('project', 'users', 'todoTasks', 'inProgressTasks', 'qaTasks', 'completedTasks'));
    }
    
    public function updateInline(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'field' => 'required|string',
        'value' => 'required|string'
    ]);

    // Find the project by ID
    $project = Project::findOrFail($id);

    // Update the appropriate field based on the 'field' parameter
    if ($request->field == 'name') {
        $project->name = $request->value;
    } elseif ($request->field == 'start_date') {
        $project->start_date = $request->value;
    } elseif ($request->field == 'due_date') {
        $project->due_date = $request->value;
    }

    // Save the updated project
    $project->save();

    return response()->json(['success' => true]);
}

        


    

}
