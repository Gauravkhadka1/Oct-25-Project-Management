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
