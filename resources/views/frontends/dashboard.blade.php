@extends('frontends.layouts.main')

@section('main-container')

<main>
    @php
    $allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
    $user = auth()->user();
    $username = $user->username;
    @endphp
    <div class="profile-page">
        <div class="users-tasks">
            @php
            $loggedInUser = Auth::user()->username; // Get the logged-in user's username
            @endphp
        </div>


        <div class="task-board">
            @php
            use App\Models\User;
            $users = User::all();
            use App\Models\Clients;
            $clients = Clients::all();
            $tasks = collect(); // Create an empty collection to hold all tasks

            // Add tasks from payments first
            foreach ($payments as $payment) {
            foreach ($payment->payment_tasks as $task) {
            $task->category = 'Payment';
            $task->category_name = $payment->company_name; // Store payment company name in 'category_name'
            $tasks->push($task); // Add payment tasks to the collection
            }
            }

            // Add tasks from projects second
            foreach ($projects as $project) {
            foreach ($project->tasks as $task) {
            $task->category = 'Project';
            $task->category_name = $project->name; // Store project name in 'category_name'
            $tasks->push($task); // Add project tasks to the collection
            }
            }

            // Add tasks from prospects third
            foreach ($prospects as $prospect) {
            foreach ($prospect->prospect_tasks as $task) {
            $task->category = 'Prospect';
            $task->category_name = $prospect->company_name; // Store prospect company name in 'category_name'
            $tasks->push($task); // Add prospect tasks to the collection
            }
            }
            // Add tasks from clients
            foreach ($clients as $client) {
                foreach ($client->client_tasks as $task) {
                    $task->category = 'Client';
                    $task->category_name = $client->company_name; // Store client company name in 'category_name'
                    $tasks->push($task);
                }
            }

            // Flag to check if there are any tasks
            $hasTasks = $tasks->isNotEmpty();
            $serialNo = 1;

            $tasksToDo = $tasks->filter(function ($task) {
            return $task->status === 'To Do' || $task->status === null;
            });
            $tasksInProgress = $tasks->where('status', 'In Progress');
            $tasksQA = $tasks->where('status', 'QA');
            $tasksCompleted = $tasks->where('status', 'Completed');
            $tasksClosed = $tasks->where('status', 'Closed');
            $columnNames = [
            'To Do' => $tasksToDo,
            'In Progress' => $tasksInProgress,
            'QA' => $tasksQA,
            'Completed' => $tasksCompleted,
            'Closed' => $tasksClosed
            ];
           
            @endphp

            @foreach ($columnNames as $status => $tasksCollection)
            <div class="task-column" id="{{ strtolower(str_replace(' ', '', $status)) }}" data-status="{{ $status }}">
                <div class="status-add-new">
                    <div class="{{ strtolower(str_replace(' ', '', $status)) }}-heading">
                        <img src="{{ url('frontend/images/' . strtolower(str_replace(' ', '', $status)) . '.png') }}" alt="">
                        <h3>{{ $status }}</h3>
                    </div>
                    <div class="add-new-task">
                    <button class="btn-create-new" onclick="openAddTaskModal('{{ strtolower(str_replace(' ', '', $status)) }}')">
                        <img src="frontend/images/add-new.png" alt="">
                    </button>

                    </div>
                </div>
         

                <div class="task-list">
                    @if ($tasksCollection->isEmpty())
                    <div class="no-tasks" style="height: 40px; background-color: white; display: flex; align-items: center; justify-content: center; border:none; margin-top: 10px;">
                        <p>No task in {{ $status }}</p>
                    </div>

                    @else
                    @foreach ($tasksCollection as $task)
                    <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                        <div class="task-name">
                        @if ($task->category == 'Payment')
                                <a href="{{ route('payment_task.detail', ['id' => $task->id]) }}">
                            @elseif ($task->category == 'Prospect')
                                <a href="{{ route('prospect_task.detail', ['id' => $task->id]) }}">
                            @elseif ($task->category == 'Client')
                                <a href="{{ route('client_task.detail', ['id' => $task->id]) }}">
                            @else
                                <a href="{{ route('task.detail', ['id' => $task->id]) }}">
                            @endif

                                        <p>{{ $task->name }}</p>
                                    </a>
                        </div>
                        <div class="in-project">in {{ $task->category_name }}</div>
                        <div class="assigne">
                            @if ($task->assignedBy)
                            <img src="{{ url('frontend/images/assignedby.png') }}" alt="">
                            by: <img src="{{ asset('storage/profile_pictures/' . $task->assignedBy->profilepic) }}"
                                alt="{{ $task->assignedBy->username }}'s Profile Picture" class="profile-pic" id="assigned-pic"> {{ $task->assignedBy->username }}
                            @else
                            <img src="{{ url('frontend/images/assignedby.png') }}" alt="Unassigned">
                            by: N/A
                            @endif
                        </div>
                        <div class="due-date">
                            <img src="{{ url('frontend/images/duedate.png') }}" alt="">
                            : {{ $task->due_date }}
                        </div>
                        <div class="priority">
                            <img src="{{ url('frontend/images/priority.png') }}" alt="">
                            : {{ $task->priority }}
                        </div>
                        <div class="time-details">
                            <div class="start-pause">
                                <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }}, '{{ $task->category }}')">
                                    <img src="{{ url('frontend/images/play.png') }}" alt="">
                                </button>
                            </div>
                            <div class="time-data" id="time-{{ $task->id }}">00:00:00</div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div id="add-task-modal" class="hidden">
                    <form action="{{ route('clientstasks.store') }}" method="POST" class="custom-form">
                        @csrf
                        <input type="hidden" id="task-status" name="status" value="">
                        <div class="task-name">
                            <input type="text" id="task-name" name="name" class="task-input" placeholder="Task Name" required />
                            <button type="submit" class="btn-save-task">Save</button>
                        </div>
                        <div class="in">
                            <img src="" alt="">
                           <!-- Dropdown for Client Selection -->
                            <div class="custom-dropdown">
                                <!-- Search Bar with Icon -->
                                <div class="search-container" style="display: none;">
                                    <img src="{{ url('frontend/images/search-icon.png') }}" alt="Search Icon" class="search-icon-client">
                                    <input type="text" id="project-search" class="task-input-client" placeholder="Search clients..." onkeyup="searchClients()" />
                                </div>

                                <!-- Dropdown Label -->
                                <div id="project-dropdown" class="dropdown-label" onclick="toggleDropdown()">
                                    <span id="selected-project">Select Client</span>
                                </div>

                                <!-- List of Clients -->
                                <div id="project-list" class="dropdown-list" style="display: none;">
                                    @foreach($clients as $client)
                                    <div class="dropdown-item" data-id="{{ $client->id }}" onclick="selectClient('{{ $client->id }}', '{{ $client->company_name }}')">
                                        {{ $client->company_name }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Hidden Input for Selected Client ID -->
                            <input type="hidden" id="client-id" name="client_id">

                        </div>


                        <div class="assigne">
                            <img src="{{ url('frontend/images/assignedby.png') }}" alt="">
                            <select id="assigned-to" name="assigned_to" class="task-input" required>
                                <option value="">Assign to</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="due-date">
                            <img src="{{ url('frontend/images/duedate.png') }}" alt="">
                            <input type="date" id="due-date" name="due_date" class="task-input" required />
                        </div>
                        <div class="priority">
                            <img src="{{ url('frontend/images/priority.png') }}" alt="">
                            <select id="priority" name="priority" class="task-input" required>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                    </form>
                </div>

        <div class="schedule-table">
            <div class="schedule-table-heading">
                <h2>{{ $username }} Today Schedule</h2>
                <form method="GET" action="{{ route('dashboard') }}">
                    <label for="schedule-date">View Schedule:</label>
                    <input type="date" id="schedule-date" name="date" value="{{ request('date', now()->toDateString()) }}" onchange="this.form.submit()">
                </form>
            </div>

            <table class="task-table">
                <thead class="schedule head">
                    <tr>
                        <th>Time Interval</th>
                        <th>Task Name</th>
                        <th>Project Name</th>
                        <th>Time Spent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hourlySessionsData as $interval => $tasks)
                    @if (count($tasks) === 1 && $tasks->first()['task_name'] === 'N/A')
                    <tr>
                        <td>{{ $interval }}</td>
                        <td colspan="3">No tasks during this interval</td>
                    </tr>
                    @else
                    @php $isFirstTask = true; @endphp
                    @foreach($tasks as $task)
                    <tr>
                        <td>{{ $isFirstTask ? $interval : '' }}</td>
                        <td>{{ $task['task_name'] }}</td>
                        <td>{{ $task['project_name'] }}</td>
                        <td>{{ $task['time_spent'] }}</td>
                    </tr>
                    @php $isFirstTask = false; @endphp
                    @endforeach
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Summary - Total Time Spent per Task</th>
                    </tr>
                    @foreach($taskSummaryData as $summary)
                    <tr>
                        <td colspan="2">{{ $summary['task_name'] }}</td>
                        <td>{{ $summary['project_name'] }}</td>
                        <td>{{ $summary['total_time_spent'] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"><strong>Total Time Spent Today on All Tasks</strong></td>
                        <td><strong>{{ $totalTimeSpentAcrossTasksFormatted }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </div>

    <script>
        let timers = {};
// Detect page unload or navigation
window.addEventListener("beforeunload", function (e) {
// Check if any timer is running
const isAnyTimerRunning = Object.values(timers).some(timer => timer.running);

// If a timer is running, show a confirmation prompt
if (isAnyTimerRunning) {
// Custom message for modern browsers (this is mostly ignored now)
const message = "Please pause the time before going to another page.";
e.returnValue = message;  // Standard for modern browsers
return message;  // For some older browsers (rarely used now)
}
});


// Load the saved time from the database when the page loads
window.addEventListener("load", () => {
// Initialize timers for Project tasks
function initializeTimers(tasks, category) {
    tasks.forEach(task => {
        timers[task.id] = {
            elapsedTime: task.elapsed_time * 1000,
            running: false
        };
        console.log(`Timer initialized for ${category} task ID:`, task.id);
        updateTimerDisplay(task.id);
    });
}

window.addEventListener("load", () => {
    initializeTimers(@json(collect($projects)->flatMap(function($p) { return $p->tasks; })->toArray()), 'Project');
initializeTimers(@json(collect($payments)->flatMap(function($p) { return $p->payment_tasks; })->toArray()), 'Payment');
initializeTimers(@json(collect($prospects)->flatMap(function($p) { return $p->prospect_tasks; })->toArray()), 'Prospect');
initializeTimers(@json(collect($clients)->flatMap(function($p) { return $p->client_tasks; })->toArray()), 'Client');

});

});



// Modify toggleTimer to ensure that it pauses the timer before leaving

function toggleTimer(taskId, taskCategory) {
const timer = timers[taskId];
const button = document.getElementById(`toggle-${taskId}`);
const buttonImage = button.querySelector('img'); // Get the image inside the button

if (timer) {
if (timer.running) {
    // If timer is running, pause it
    pauseTimer(taskId, taskCategory);
    buttonImage.src = "{{ url('frontend/images/play.png') }}"; // Show play icon
    button.classList.remove("pause");
    button.classList.add("start");
} else {
    // If timer is paused or not started, start/resume it
    startTimer(taskId, taskCategory);
    buttonImage.src = "{{ url('frontend/images/pause.png') }}"; // Show pause icon
    button.classList.remove("start");
    button.classList.add("pause");
}
} else {
console.error(`Timer not found for task ID: ${taskId}`);
}
}


function startTimer(taskId, taskCategory) {
const timer = timers[taskId];
if (timer) {
timer.startTime = new Date().getTime() - timer.elapsedTime;
timer.running = true;

console.log(`Starting/resuming timer for task ID: ${taskId} of category ${taskCategory}`);
sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/start-timer`, taskCategory);

updateTimer(taskId);
}
}

function pauseTimer(taskId, taskCategory) {
const timer = timers[taskId];
if (timer && timer.running) {
timer.elapsedTime = new Date().getTime() - timer.startTime;
timer.running = false;

console.log(`Pausing timer for task ${taskId} of category ${taskCategory}, elapsed time: ${timer.elapsedTime}`);

sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/pause-timer`, taskCategory);
}
}
function updateTimer(taskId) {
    const timer = timers[taskId];
    if (timer && timer.running) {
        const currentTime = new Date().getTime() - timer.startTime;
        timer.elapsedTime = currentTime;
        
        updateTimerDisplay(taskId);
        
        setTimeout(() => updateTimer(taskId), 1000);
    }
}

function updateTimerDisplay(taskId) {
    const timer = timers[taskId];
    if (timer) {
        const totalSeconds = Math.floor(timer.elapsedTime / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        
        document.getElementById(`time-${taskId}`).innerText = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
}
function sendTimerUpdate(taskId, elapsedTime, url, taskCategory) {
fetch(url, {
method: 'POST',
headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
},
body: JSON.stringify({
    elapsed_time: elapsedTime,
    task_category: taskCategory // Ensure correct category is sent
})
})
.then(response => response.json())
.then(data => {
console.log("Timer updated:", data);
})
.catch(error => {
console.error("Error updating timer:", error);
});
}



// Handle status change for task, payment task, and prospect task
document.querySelectorAll('.task-status, .payment-task-status, .prospect-task-status, .client-task-status').forEach(statusElement => {
statusElement.addEventListener('change', function () {
const taskId = this.name.match(/\d+/)[0];
const status = this.value;
const taskType = this.classList.contains('payment-task-status') ? 'payment' : 
                 this.classList.contains('client-task-status') ? 'client' :
                 this.classList.contains('prospect-task-status') ? 'prospect' : 'task'; // Determine the task type

fetch(`/tasks/update-status-comment`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is correct
    },
    body: JSON.stringify({ taskId, status, taskType }) // Add taskType to distinguish between task types
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Status updated successfully.');
    } else {
        console.error('Failed to update status.');
    }
})
.catch(error => console.error('Error:', error));
});
});

// Handle comment change for task, payment task, and prospect task
document.querySelectorAll('.task-comment, .payment-task-comment, .prospect-task-comment').forEach(commentElement => {
commentElement.addEventListener('change', function () {
const taskId = this.name.match(/\d+/)[0];
const comment = this.value;
const taskType = this.classList.contains('payment-task-comment') ? 'payment' : 
                 this.classList.contains('prospect-task-comment') ? 'prospect' : 'task'; // Determine the task type

fetch(`/tasks/update-comment`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is correct
    },
    body: JSON.stringify({ task_id: taskId, comment, taskType }) // Add taskType to distinguish between task types
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Comment updated successfully.');
    } else {
        console.error('Failed to update comment.');
    }
})
.catch(error => console.error('Error:', error));
});
});



// Prospect Task Timer Ends 
 // JavaScript for drag-and-drop functionality
const tasks = document.querySelectorAll('.task');
const columns = document.querySelectorAll('.task-column');

// Enable drag-and-drop
tasks.forEach(task => {
task.addEventListener('dragstart', () => {
task.classList.add('dragging');
});

task.addEventListener('dragend', () => {
task.classList.remove('dragging');
});
});

// Update task status on drop
columns.forEach(column => {
column.addEventListener('dragover', (e) => {
e.preventDefault();
});

column.addEventListener('drop', (e) => {
e.preventDefault();
const draggingTask = document.querySelector('.dragging');
const taskId = draggingTask.getAttribute('data-task-id');
const taskType = draggingTask.getAttribute('data-task-type');
const newStatus = column.getAttribute('data-status');

// Move task to new column
column.querySelector('.task-list').appendChild(draggingTask);

// AJAX request to update task status in the database
fetch("{{ route('tasks.updateStatusComment') }}", {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    body: JSON.stringify({ taskId, taskType, status: newStatus })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log(`Task ${taskId} status updated to ${newStatus}`);
    } else {
        console.error("Failed to update task status");
    }
})
.catch(error => console.error("Error:", error));
});
});


// Open Add Task Modal

let currentOpenModal = null;  // Track the currently open modal

function openAddTaskModal(columnId) {
    const modal = document.getElementById('add-task-modal');
    if (!modal) return;

    // Only open the modal if it's not already open
    if (!currentOpenModal) {
        // Move the modal to the correct column
        const column = document.getElementById(columnId);
        const taskList = column.querySelector('.task-list');
        if (taskList) {
            taskList.insertBefore(modal, taskList.firstChild);
        }

        // Update the hidden input field with the column's status
        const columnField = document.getElementById('task-status');
        if (columnField) {
            columnField.value = column.getAttribute('data-status');  // Ensure it uses the correct status from the column
        }

        // Show the modal
        modal.classList.remove('hidden');
        modal.style.display = 'block';

        // Set the current open modal to prevent multiple modals in one column
        currentOpenModal = modal;

        // Add event listener to close the modal when clicking outside
        setTimeout(() => document.addEventListener('click', handleOutsideClick), 0);
    }
}

function closeAddTaskModal() {
    const modal = document.getElementById('add-task-modal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.style.display = 'none';

    // Reattach the modal to the body to ensure it's not stuck in one column
    const body = document.querySelector('body');
    body.appendChild(modal);

    // Reset the modal fields
    document.getElementById('task-name').value = '';
    document.getElementById('assigned-to').value = '';
    document.getElementById('due-date').value = '';
    document.getElementById('priority').value = 'Normal';
    document.getElementById('task-status').value = '';

    // Clear the current open modal reference
    currentOpenModal = null;

    // Remove event listener for outside clicks
    document.removeEventListener('click', handleOutsideClick);
}

function handleOutsideClick(event) {
    const modal = document.getElementById('add-task-modal');
    const isClickInsideModal = modal.contains(event.target);
    const isClickInsideButton = document.querySelector('.btn-create-new').contains(event.target); // Check if the click is inside the button

    // Close the modal if the click is outside the modal and the button
    if (!isClickInsideModal && !isClickInsideButton) {
        closeAddTaskModal();
    }
}


// Save Task and Update Column
function saveTask() {
    const taskName = document.getElementById('task-name').value;
    const assignedTo = document.getElementById('assigned-to').value;
    const dueDate = document.getElementById('due-date').value;
    const priority = document.getElementById('priority').value;
    const status = document.getElementById('task-status').value;

    console.log(`Saving task with status: ${status}`); // Debugging: log the status being sent

    if (!taskName || !assignedTo || !dueDate || !priority || !status) {
        alert('Please fill in all fields!');
        return;
    }

    const data = {
        name: taskName,
        assigned_to: assignedTo,
        due_date: dueDate,
        priority: priority,
        status: status,
        _token: '{{ csrf_token() }}',
    };

    fetch('{{ route('tasks.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to save task.');
        return response.json();
    })
    .then(task => {
        console.log('Task saved successfully:', task); // Debugging: log the saved task
        const taskList = document.querySelector(`#${status} .task-list`);
        if (taskList) {
            const newTask = `
                <div class="task" draggable="true" data-task-id="${task.id}">
                    <div class="task-name">
                        <a href="">
                            <p>${task.name}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="{{ url('frontend/images/assignedby.png') }}" alt=""> to: ${task.assigned_to_username}
                    </div>
                    <div class="due-date">
                        <img src="{{ url('frontend/images/duedate.png') }}" alt=""> : ${task.due_date}
                    </div>
                    <div class="priority">
                        <img src="{{ url('frontend/images/priority.png') }}" alt=""> : ${task.priority}
                    </div>
                </div>
            `;
            taskList.insertAdjacentHTML('afterbegin', newTask);
        }

        closeAddTaskModal();

        // Reset the modal fields
        document.getElementById('task-name').value = '';
        document.getElementById('assigned-to').value = '';
        document.getElementById('due-date').value = '';
        document.getElementById('priority').value = 'Normal';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save task. Please try again.');
    });
}



// Toggle dropdown visibility
// Toggle dropdown visibility
function toggleDropdown() {
    const projectList = document.getElementById('project-list');
    const searchContainer = document.querySelector('.search-container');

    // Toggle visibility of the project list and search bar
    const isVisible = projectList.style.display === 'block';
    projectList.style.display = isVisible ? 'none' : 'block';
    searchContainer.style.display = isVisible ? 'none' : 'flex'; // Use 'flex' to match CSS
}

// Select a client and update the label
function selectClient(clientId, clientName) {
    // Update the hidden input with the selected client's ID
    document.getElementById('client-id').value = clientId;

    // Update the dropdown label with the selected client's name
    document.getElementById('selected-project').textContent = clientName;

    // Hide the dropdown list and search bar
    document.getElementById('project-list').style.display = 'none';
    document.getElementById('project-search').style.display = 'none';
    
    // Reset search input value after client selection
    document.getElementById('project-search').value = '';
}

// Filter the client list based on search input
function searchClients() {
    const input = document.getElementById('project-search').value.toLowerCase();
    const items = document.getElementsByClassName('dropdown-item');

    // Loop through the items and display/hide based on match
    Array.from(items).forEach(item => {
        const projectName = item.textContent.toLowerCase();
        item.style.display = projectName.includes(input) ? 'block' : 'none';
    });
}


</script>
</main>
@endsection