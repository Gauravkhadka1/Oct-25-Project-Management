@extends('frontends.layouts.main')

@section('main-container')

@php
    use Carbon\Carbon;
@endphp

    <main>
        <div class="project-page">
        <div class="project-heading">
             <h2>Projects</h2>
           </div>
        <!-- <div class="project-tags">
            <span>Sabin M..</span>
            <span>Anubhav M..</span>
            <span>Lokendra GC</span>
            <span>Denisha M..</span>
            <span>Jeena M..</span>
            <span>Muskaan T..</span>
            <span>Sabita B..</span>
            <span>Gaurav K..</span>
            <span>Suraj Sir</span>
            <span>Sudeep Sir</span>
        </div> -->
    <div class="projects">

                <div class="mob-heading">
                    <div class="mob-heading-recent" onclick="toggleTable('ongoing', this)">
                        <h2>Ongoing</h2>
                    </div>
                    <div class="mob-heading-top" onclick="toggleTable('completed', this)">
                        <h2>Completed</h2>
                    </div>
                    <div class="mob-heading-top" onclick="toggleTable('closed', this)">
                        <h2>Closed</h2>
                    </div>
                </div>
                <div class="ongoing-project" id="ongoing-project">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Project</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>
                                Status
                                <a href="#" onclick="toggleFilter('category-filter')">
                                    <i class="fas fa-filter"></i> <!-- Filter Icon -->
                                </a>
                                <div id="category-filter" class="filter-dropdown" style="display: none;">
                                    <form action="{{ route('prospects.index') }}" method="GET">
                                        <select name="filter_category" onchange="this.form.submit()">
                                            <option value="">All Categories</option>
                                            <option value="Ecommerce" {{ request('filter_category') == 'Design' ? 'selected' : '' }}>Design</option>
                                            <option value="NGO/ INGO" {{ request('filter_category') == 'Development' ? 'selected' : '' }}>Development</option>
                                            <option value="Tourism" {{ request('filter_category') == 'QA' ? 'selected' : '' }}>QA</option>
                                            <option value="Education" {{ request('filter_category') == 'ContentFillup' ? 'selected' : '' }}>Content Fillup</option>
                                            <option value="Education" {{ request('filter_category') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Education" {{ request('filter_category') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                            <option value="Other" {{ request('filter_category') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </form>
                                </div>
                            </th>
                            <th>Time left</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $key => $project)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><a href="javascript:void(0);" onclick="openTaskDetailsModal({{ json_encode($project) }})">{{ $project->name }}</a></td>

                            <td>{{ $project->start_date }}</td>
                            <td>{{ $project->due_date }}</td>
                            <td>{{ $project->status }}</td>
                            <td>
                                @php
                                    // Parse the dates with Carbon
                                    $startDate = \Carbon\Carbon::parse($project->start_date);
                                    $dueDate = \Carbon\Carbon::parse($project->due_date);
                                    $currentDate = \Carbon\Carbon::now(); // Get the current date
                                    
                                    // Calculate the number of full days left from current date to the due date
                                    $daysLeft = $currentDate->startOfDay()->diffInDays($dueDate, false); // false allows negative values (for overdue)
                                @endphp

                                {{-- Displaying the remaining days --}}
                                @if($daysLeft > 0)
                                    {{ $daysLeft }} days left
                                @elseif($daysLeft === 0)
                                    Due today
                                @else
                                    Overdue by {{ abs($daysLeft) }} days
                                @endif
                            </td>
                            <td>
                                <button class="btn-create" onclick="openEditProjectModal({{ json_encode($project) }})">Edit</button>
                                <form action="{{ route('project.destroy', $project->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-cancel">Delete</button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="create-project">
                    <button onclick="openCreateProjectModal()">Create Project</button>
                </div>
    </div>
        </div>

        <!-- Modal for Creating New Project -->
        <div id="create-project-modal" style="display: none;">
            <div>
                <h3>Create New Project</h3>
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <label for="project-name">Project Name:</label>
                    <input type="text" name="name" id="project-name" >

                    <label for="start-date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" >

                    <label for="due-date">Due Date:</label>
                    <input type="date" name="due_date" id="due_date" >

                    <label for="assignee">Status</label>
                    <select name="status" id="status" >
                        <option value="Design">Design</option>
                        <option value="Development">Development</option>
                        <option value="QA">QA</option>
                        <option value="Content fillup">Content fillup</option>
                        <option value="Content fillup">Other</option>
                    </select>

                    <button type="submit">Add Project</button>
                    <button type="button" onclick="closeCreateProjectModal()">Cancel</button>
                </form>
            </div>
        </div>

        <!-- Modal for Editing Project -->

        <div id="edit-project-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h3>Edit Prospect</h3>
            <form id="edit-project-form" action="{{ route('projects.update', '') }}" method="POST">
                @csrf
                @method('PUT')
                <label for="edit-project-name">Project Name:</label>
                <input type="text" name="name" id="edit-project-name"><br>

                <label for="edit-start-date">Start Date:</label>
                    <input type="date" name="start_date" id="edit-start_date" >

                    <label for="edit-due-date">Due Date:</label>
                    <input type="date" name="due_date" id="edit-due_date" >

                    <label for="assignee">Status</label>
                    <select name="status" id="edit-status" >
                        <option value="Design">Design</option>
                        <option value="Development">Development</option>
                        <option value="QA">QA</option>
                        <option value="Content fillup">Content fillup</option>
                        <option value="Content fillup">Other</option>
                    </select>

                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Update Prospect</button>
                    <button type="button" class="btn-cancel" onclick="closeEditProjectModal()">Cancel</button>
                </div>
            </form>
        </div>
   </div>

        <!-- Modal for Task Details -->
        <div id="task-details-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeTaskDetailsModal()">&times;</span>
                <h3 id="project-name-modal">Project Tasks</h3>
                
                <!-- Add Task Button -->
                <button class="btn-create" onclick="openAddTaskModal()">Add Task</button>

                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Assigned By</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Time Taken</th>
                        </tr>
                    </thead>
                    <tbody id="task-details-body">
                        <!-- Task details will be populated here dynamically -->
        
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Adding New Task -->
        <div id="add-task-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeAddTaskModal()">&times;</span>
                <h3>Add New Task</h3>
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="project-id" name="project_id">

                    <label for="task-name">Task Name:</label>
                    <input type="text" name="name" id="task-name" >

                    <label for="assigned-to">Assigned To:</label>
                    <input type="text" name="assigned_to" id="assigned-to" >

                    <label for="start-date">Start Date:</label>
                    <input type="date" name="start_date" id="start-date" >

                    <label for="due-date">Due Date:</label>
                    <input type="date" name="due_date" id="due-date" >

                    <label for="priority">Priority:</label>
                    <select name="priority" id="priority">
                        <option value="Normal">Normal</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>

                    <button type="submit">Add Task</button>
                    <button type="button" onclick="closeAddTaskModal()">Cancel</button>
                </form>
            </div>
        </div>


   

        <script>
                function openCreateProjectModal() {
                    document.getElementById('create-project-modal').style.display = 'block';
                }

                function closeCreateProjectModal() {
                    document.getElementById('create-project-modal').style.display = 'none';
                }

                function openEditProjectModal(project) {
                    document.getElementById('edit-project-name').value = project.name;
                    document.getElementById('edit-start_date').value = project.start_date; // Fixed the ID
                    document.getElementById('edit-due_date').value = project.due_date; // Fixed the ID
                    document.getElementById('edit-status').value = project.status;

                    // Set the form action to include the project ID
                    const form = document.getElementById('edit-project-form');
                    form.action = "{{ route('projects.update', '') }}/" + project.id;

                    document.getElementById('edit-project-modal').style.display = 'block';
                }


            function closeEditProjectModal() {
                document.getElementById('edit-project-modal').style.display = 'none';
            }


                // for filter
                function toggleFilter(id) {
                    // Close other filters first
                    document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
                        if (dropdown.id !== id) {
                            dropdown.style.display = 'none';
                        }
                    });

                    // Toggle the selected filter dropdown
                    var element = document.getElementById(id);
                    if (element.style.display === 'none' || element.style.display === '') {
                        element.style.display = 'block';
                    } else {
                        element.style.display = 'none';
                    }
                }

                // Close the dropdowns if clicked outside, but ignore clicks inside the dropdown
                window.onclick = function(event) {
                    // Check if the click is inside the dropdown or on the filter icon
                    if (!event.target.closest('.filter-dropdown') && !event.target.matches('.fas')) {
                        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
                            dropdown.style.display = 'none';
                        });
                    }
                }

        // show Task details models
        function openTaskDetailsModal(project) {
                    console.log(project); // Log the entire project object

                    // Set project name in modal heading
                    document.getElementById('project-name-modal').innerText = project.name + " - Task Details";

                    // Get the task details (assumed to be loaded as part of the project object)
                    let tasks = project.tasks || []; // Ensure tasks is an array
                    console.log(tasks); // Check tasks array

                    let taskDetailsBody = document.getElementById('task-details-body');
                    taskDetailsBody.innerHTML = ''; // Clear previous content

                    // Loop through tasks and create table rows
                    tasks.forEach(task => {
                        // Check if task has necessary properties
                        if (task) {
                            let row = `<tr>
                                <td>${task.name || 'N/A'}</td>
                                <td>${task.assigned_to || 'N/A'}</td>
                                <td>${task.assigned_by || 'N/A'}</td>
                                <td>${task.start_date ? new Date(task.start_date).toISOString().split('T')[0] : 'N/A'}</td>
                                <td>${task.due_date ? new Date(task.due_date).toISOString().split('T')[0] : 'N/A'}</td>
                                <td>${task.priority || 'N/A'}</td>
                                <td>${task.time_taken || 0} hours</td>
                            </tr>`;
                            taskDetailsBody.innerHTML += row;
                        }
                    });

                    // Display the modal
                    document.getElementById('task-details-modal').style.display = 'block';
                }


            function closeTaskDetailsModal() {
                document.getElementById('task-details-modal').style.display = 'none';
            }

            function openAddTaskModal() {
                document.getElementById('add-task-modal').style.display = 'block';
            }

            function closeAddTaskModal() {
                document.getElementById('add-task-modal').style.display = 'none';
            }



        </script>     

    </main>

@endsection