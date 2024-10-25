<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task; 
use App\Models\User; 

class ProjectController extends Controller
{
    public function index()
    {
        // Fetch all projects with their tasks
        $projects = Project::with('tasks')->get(); 
        $users = User::all(); 

        // Initialize $project to null if there are no projects
        $project = $projects->isEmpty() ? null : $projects;

        // Pass the initialized $project and $users to the view
        return view('frontends.projects', compact('users', 'projects', 'project'));
    }

    public function store(Request $request)
    {
        // Validate and save project
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:Design,Development,QA,Content fillup,Other',  
        ]);

        Project::create($validatedData);

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
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
        ]);

        // Find the project
        $project = Project::findOrFail($id);
        // Update the project
        $project->update($request->all());

        // Redirect with success message
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
}
