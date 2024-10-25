<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task; 

class ProjectController extends Controller
{
    public function index()
    {
        // Fetch all projects
        $projects = Project::with('tasks')->get(); 
        return view('frontends.projects', compact('projects'));
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
        $project = Project::findOrFail($id); // Find the prospect or fail
        $project->delete(); // Delete the prospect

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


}


