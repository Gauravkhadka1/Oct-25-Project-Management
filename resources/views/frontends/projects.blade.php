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
        <div class="projects">
            <div class="ongoing-project" id="ongoing-project">
                <div class="create-project">
                    <button onclick="openCreateProjectModal()"><img src="{{url ('/frontend/images/plus.png')}}" alt=""> Project</button>
                </div>
                <table class="modern-payments-table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>
                                Projects
                                <a href="#" onclick="toggleFilter('project-name-sort')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="project-name-sort" class="filter-dropdown" style="display: none;">
                                    <form action="{{ route('projects.index') }}" method="GET">
                                        <select name="sort_project_name" onchange="this.form.submit()">
                                            <option value="">Sort by Project Name</option>
                                            <option value="a_to_z" {{ request('sort_project_name') == 'a_to_z' ? 'selected' : '' }}>A to Z</option>
                                            <option value="z_to_a" {{ request('sort_project_name') == 'z_to_a' ? 'selected' : '' }}>Z to A</option>
                                        </select>
                                    </form>
                                </div>
                            </th>
                            <th>
                                Start Date
                                <a href="#" onclick="toggleFilter('date-filter')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="date-filter" class="filter-dropdown" style="display: none;">
                                    <form action="{{ route('projects.index') }}" method="GET">
                                        <select name="sort_start_date" onchange="this.form.submit()">
                                            <option value="">Sort by Start Date</option>
                                            <option value="recent" {{ request('sort_start_date') == 'recent' ? 'selected' : '' }}>Most Recent</option>
                                            <option value="oldest" {{ request('sort_start_date') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                        </select>
                                    </form>
                                </div>
                            </th>
                            <th>
                                Due Date
                                <a href="#" onclick="toggleFilter('due-date-sort')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="due-date-sort" class="filter-dropdown" style="display: none;">
                                    <form action="{{ route('projects.index') }}" method="GET">
                                        <select name="sort_due_date" onchange="this.form.submit()">
                                            <option value="">Sort by Due Date</option>
                                            <option value="more_time" {{ request('sort_due_date') == 'more_time' ? 'selected' : '' }}>Less Time</option>
                                            <option value="less_time" {{ request('sort_due_date') == 'less_time' ? 'selected' : '' }}>More Time</option>
                                        </select>
                                    </form>
                                </div>
                            </th>
                            <th>
                                Status
                                <a href="#" onclick="toggleFilter('status-filter')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="status-filter" class="filter-dropdown" style="display: none;">
                                    <form action="{{ route('projects.index') }}" method="GET">
                                        <select name="filter_status" onchange="this.form.submit()">
                                            <option value="">All Status</option>
                                            <option value="Design" {{ request('filter_status') == 'Design' ? 'selected' : '' }}>Design</option>
                                            <option value="Development" {{ request('filter_status') == 'Development' ? 'selected' : '' }}>Development</option>
                                            <option value="QA" {{ request('filter_status') == 'QA' ? 'selected' : '' }}>QA</option>
                                            <option value="Content Fillup" {{ request('filter_status') == 'ContentFillup' ? 'selected' : '' }}>Content Fillup</option>
                                            <option value="Completed" {{ request('filter_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Closed" {{ request('filter_status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                            <option value="Other" {{ request('filter_status') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </form>
                                </div>
                            </th>

                            <th>
                                Time Left
                                <a href="#" onclick="toggleFilter('time-left-sort')">
                                    <img src="frontend/images/bars-filter.png" alt="" class="barfilter">
                                </a>
                                <div id="time-left-sort" class="filter-dropdown" style="display: none;">
                                    <form action="{{ route('projects.index') }}" method="GET">
                                        <select name="sort_time_left" onchange="this.form.submit()">
                                            <option value="">Sort by Time Left</option>
                                            <option value="time_left_asc" {{ request('sort_time_left') == 'time_left_asc' ? 'selected' : '' }}>Less Days Left</option>
                                            <option value="time_left_desc" {{ request('sort_time_left') == 'time_left_desc' ? 'selected' : '' }}>More Days Left</option>
                                        </select>
                                    </form>
                                </div>
                            </th>


                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $key => $project)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $project->name }}
                                <div class="projects-name-buttons">
                                    <button class="btn-create" id="task-create" onclick="openAddTaskModal({{ $project->id }})"><img src="{{url ('/frontend/images/plus.png')}}" alt=""> Task</button>
                                    <button class="btn-view-activities-p" onclick="openTaskDetailsModal({{ json_encode($project) }})"><img src="{{url ('/frontend/images/view.png')}}" alt="">Tasks</button>
                                </div>
                            </td>


                            <td>{{ $project->start_date }}</td>
                            <td>{{ $project->due_date }}</td>
                            <td>{{ $project->status }}</td>
                            <td>
                                @if(is_null($project->start_date) || is_null($project->due_date))
                                N/A
                                @else
                                @php
                                $startDate = \Carbon\Carbon::parse($project->start_date);
                                $dueDate = \Carbon\Carbon::parse($project->due_date);
                                $currentDate = \Carbon\Carbon::now();
                                $daysLeft = $currentDate->startOfDay()->diffInDays($dueDate, false);
                                @endphp

                                @if($daysLeft > 0)
                                {{ $daysLeft }} days left
                                @elseif($daysLeft === 0)
                                Due today
                                @else
                                Overdue by {{ abs($daysLeft) }} days
                                @endif
                                @endif
                            </td>
                            <!-- Add a button to add tasks for each project -->
                            <td>

                                <button class="btn-edit" onclick="openEditProjectModal({{ json_encode($project) }})"><img src="{{url ('/frontend/images/edit.png')}}" alt=""></button>
                                <!-- <form action="{{ route('project.destroy', $project->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-cancel">Delete</button>
                                    </form> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Creating New Project -->
        <div id="create-project-modal" style="display: none;">
            <div>
                <h3>Create New Project</h3>
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <label for="project-name">Project Name:</label>
                    <input type="text" name="name" id="project-name">

                    <label for="start-date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date">

                    <label for="due-date">Due Date:</label>
                    <input type="date" name="due_date" id="due_date">

                    <label for="assignee">Status</label>
                    <select name="status" id="status">
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
                    <input type="date" name="start_date" id="edit-start_date">

                    <label for="edit-due-date">Due Date:</label>
                    <input type="date" name="due_date" id="edit-due_date">

                    <label for="assignee">Status</label>
                    <select name="status" id="edit-status">
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


                <table class="styled-table-project">
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
        <div id="add-task-modal" class="custom-modal" style="display: none;">
            <div class="custom-modal-content">
                <span class="custom-close" onclick="closeAddTaskModal()">&times;</span>
                <h3 class="custom-modal-title">Add New Task</h3>
                <form action="{{ route('tasks.store') }}" method="POST" class="custom-form">
                    @csrf
                    <input type="hidden" id="project-id" name="project_id" value="">

                    <label for="task-name" class="custom-label">Task Name:</label>
                    <input type="text" name="name" id="task-name" class="custom-input" required>

                    <label for="assigned-to" class="custom-label">Assigned To:</label>
                    <select name="assigned_to" id="assigned-to" class="custom-select" required>
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->email }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>

                    <label for="start-date" class="custom-label">Start Date:</label>
                    <input type="date" name="start_date" id="start-date" class="custom-input">

                    <label for="due-date" class="custom-label">Due Date:</label>
                    <input type="date" name="due_date" id="due-date" class="custom-input">

                    <label for="priority" class="custom-label">Priority:</label>
                    <select name="priority" id="priority" class="custom-select">
                        <option value="Normal">Normal</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>

                    <button type="submit" class="custom-submit-button">Add Task</button>
                    <button type="button" class="custom-cancel-button" onclick="closeAddTaskModal()">Cancel</button>
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

            function openAddTaskModal(projectId) {
                // Set the hidden input for project ID
                document.getElementById('project-id').value = projectId;
                console.log('Opening modal for project ID:', projectId);
                document.getElementById('add-task-modal').style.display = 'block';
            }
            var project = @json($project); // Pass project data as JSON
            let timers = {};

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
                        let assignedToUsername = task.assigned_user ? task.assigned_user.username : 'N/A'; // Use the username instead of ID
                        let assignedByUsername = task.assigned_by ? task.assigned_by.username : 'N/A'; // If you want to display assigned by username as well

                        // Initialize timer for the task
                        if (!timers[task.id]) {
                            timers[task.id] = {
                                elapsedTime: task.elapsed_time * 1000 || 0, // Convert to milliseconds
                                running: false
                            };
                        }

                        // Create the row with a timer display
                        let row = `<tr>
                    <td>${task.name || 'N/A'}</td>
                    <td>${assignedToUsername}</td>
                    <td>${assignedByUsername}</td>
                    <td>${task.start_date ? new Date(task.start_date).toISOString().split('T')[0] : 'N/A'}</td>
                    <td>${task.due_date ? new Date(task.due_date).toISOString().split('T')[0] : 'N/A'}</td>
                    <td>${task.priority || 'N/A'}</td>
                    <td id="time-${task.id}">${formatTime(timers[task.id].elapsedTime)}</td>
                </tr>`;
                        taskDetailsBody.innerHTML += row;

                        // Update the timer display for each task
                        updateTimerDisplay(task.id);
                    }
                });
                document.getElementById('task-details-modal').style.display = 'block';

            }

            function formatTime(milliseconds) {
                const totalSeconds = Math.floor(milliseconds / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            function updateTimerDisplay(taskId) {
                const timer = timers[taskId];
                if (timer) {
                    const totalSeconds = Math.floor(timer.elapsedTime / 1000);
                    document.getElementById(`time-${taskId}`).innerText = formatTime(timer.elapsedTime);
                }
            }

            function closeTaskDetailsModal() {
                document.getElementById('task-details-modal').style.display = 'none';
            }

            function closeAddTaskModal() {
                document.getElementById('add-task-modal').style.display = 'none';
            }

            function toggleFilter(filterId) {
                // Close all other filters first
                document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
                    if (dropdown.id !== filterId) {
                        dropdown.style.display = 'none';
                    }
                });

                // Toggle the selected filter dropdown
                const element = document.getElementById(filterId);
                if (element) {
                    element.style.display = (element.style.display === 'none' || element.style.display === '') ? 'block' : 'none';
                }
            }

            // Close dropdowns when clicking outside
            window.onclick = function(event) {
                if (!event.target.matches('.barfilter') && !event.target.closest('.filter-dropdown')) {
                    document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
                        dropdown.style.display = 'none';
                    });
                }
            };
        </script>


</main>

@endsection