@extends('frontends.layouts.main')

@section('main-container')

@if (session('success'))
<div id="success-message" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    {{ session('success') }}
</div>

@endif

<div id="success-message" style="display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #28a745; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    <!-- Success message will be dynamically inserted here -->
</div>
<div class="project-name-status">
<div class="project-n">
    <p>{{ $project->name }} Tasks</p>
</div>


</div>
<div class="task-board" id="project-details-page" >
    <!-- Column for To Do tasks -->
    <div class="task-column" id="todo" data-status="TO DO">
        <div class="todo-add">
        <div class="todo-heading">
                    <img src="{{url ('public/frontend/images/todo.png')}}" alt="">
                    <h3>TO DO</h3>
                </div>
            <div class="add-icon">
                <button class="btn-create-new" id="task-create" onclick="openAddTaskModal({{ $project->id }})">
                    <img src="{{ url('public/frontend/images/add-new.png') }}" alt="" style="width: 15px; margin-right:10px;">
                </button>
            </div>
        </div>
  <!-- Add Task Form -->
  <div id="add-task-modal" class="hidden">
        <form action="{{ route('tasks.store') }}" method="POST" class="custom-form">
            @csrf
            <input type="hidden" id="project-id" name="project_id" value="">

            <div class="task-name">
                <input type="text" id="task-name" name="name" class="task-input" placeholder="Task Name" required />
                <button type="submit" class="btn-save-task">Save</button>
            </div>
            <div class="assigne">
                <img src="{{ url('public/frontend/images/assignedby.png') }}" alt="">
                <select id="assigned-to" name="assigned_to" class="task-input" required>
                    <option value="">Assign to</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="project-start-date">
    <div class="start-date-input">
        <img src="{{ url('public/frontend/images/start-date.png') }}" alt="">
        <input type="text" id="start-date" name="start_date" class="task-input" placeholder="Start Date" readonly required />
    </div>
</div>

<div class="project-due-date">
    <div class="due-date-input">
        <img src="{{ url('public/frontend/images/end-date.png') }}" alt="">
        <input type="text" id="due-date" name="due_date" class="task-input" placeholder="Due Date" readonly required />
    </div>
</div>

            <div class="priority">
                <img src="{{ url('public/frontend/images/priority.png') }}" alt="">
                <select id="priority" name="priority" class="task-input" required>
                    <option value="Normal">Normal</option>
                    <option value="High">High</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>
            
            <!-- <div class="task-actions">
            
                <button type="button" class="btn-cancel-task" onclick="toggleAddTaskForm()">Cancel</button>
            </div> -->
        </form>

     </div>
        <!-- Task List -->
        <div class="task-list">
            @foreach ($todoTasks as $task)
                <div class="task" draggable="true">
                    <div class="task-name">
                       <a href="{{ route('task.detail', ['id' => $task->id]) }}">
                        <p>{{ $task->name }}</p>
                    </a>
                    </div>
                    <div class="assigne">
                        @if ($task->assignedTo)
                            <img src="{{ url('public/frontend/images/assigned-to.png') }}" alt="" class="assigned-to-icon">
                            : <img src="{{ asset('storage/profilepics/' . $task->assignedTo->profilepic) }}" 
                            alt="{{ $task->assignedTo->username }}'s Profile Picture" class="profile-pic" id="assigned-pic"> 
                        @else
                            <img src="{{ url('public/frontend/images/unassigned.png') }}" alt="Unassigned">
                            by: N/A
                        @endif
                    </div>
                    <div class="due-date" style="margin-top: 4px;">
    <img src="{{ url('public/frontend/images/due-date.png') }}" alt=""> :
    @if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours))
        @if(isset($task->remaining_days) && $task->remaining_days > 0)
            {{ $task->remaining_days }} days {{ $task->remaining_hours }} hours left
        @elseif(isset($task->remaining_hours) && $task->remaining_hours > 0)
            {{ $task->remaining_hours }} hours left
        @elseif(isset($task->overdue_days) && $task->overdue_days > 0)
            Overdue by {{ $task->overdue_days }} days {{ $task->overdue_hours }} hours
        @elseif(isset($task->overdue_hours) && $task->overdue_hours > 0)
            Overdue by {{ $task->overdue_hours }} hours
        @else
            Due now
        @endif
    @else
        N/A
    @endif
</div>



                    <div class="priority">
                                @php
                                    $priorityImages = [
                                        'High' => 'priority-high.png',
                                        'Urgent' => 'priority-urgent.png',
                                        'Normal' => 'priority-normal.png',
                                    ];
                                    $priorityImage = isset($priorityImages[$task->priority]) ? $priorityImages[$task->priority] : 'default.png';
                                @endphp
                                <img src="{{ url('public/frontend/images/' . $priorityImage) }}" alt="{{ $task->priority }}">
                                : {{ $task->priority }}
                            </div>
                     <div class="priority">
                    Time spent: {{ gmdate('H:i:s', $task->elapsed_time) }}

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Column for In Progress tasks -->
    <div class="task-column" id="in-progress" data-status="IN PROGRESS">
        <div class="todo-add">
        <div class="inprogress-heading">
                    <img src="{{url ('public/frontend/images/inprogress.png')}}" alt="">
                    <h3>IN PROGRESS</h3>
                </div>
            
        </div>

        <!-- Task List -->
        <div class="task-list">
            @foreach ($inProgressTasks as $task)
                <div class="task" draggable="true">
                    <div class="task-name">
                      <a href="{{ route('task.detail', ['id' => $task->id]) }}">
                        <p>{{ $task->name }}</p>
                    </a>
                    </div>
                    <div class="assigne">
                        @if ($task->assignedTo)
                            <img src="{{ url('public/frontend/images/assigned-to.png') }}" alt="" class="assigned-to-icon">
                            : <img src="{{ asset('storage/profilepics/' . $task->assignedTo->profilepic) }}" 
                            alt="{{ $task->assignedTo->username }}'s Profile Picture" class="profile-pic" id="assigned-pic"> 
                        @else
                            <img src="{{ url('public/frontend/images/unassigned.png') }}" alt="Unassigned">
                            by: N/A
                        @endif
                    </div>
                    <div class="due-date" style="margin-top: 4px;">
    <img src="{{ url('public/frontend/images/due-date.png') }}" alt=""> :
    @if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours))
        @if(isset($task->remaining_days) && $task->remaining_days > 0)
            {{ $task->remaining_days }} days {{ $task->remaining_hours }} hours left
        @elseif(isset($task->remaining_hours) && $task->remaining_hours > 0)
            {{ $task->remaining_hours }} hours left
        @elseif(isset($task->overdue_days) && $task->overdue_days > 0)
            Overdue by {{ $task->overdue_days }} days {{ $task->overdue_hours }} hours
        @elseif(isset($task->overdue_hours) && $task->overdue_hours > 0)
            Overdue by {{ $task->overdue_hours }} hours
        @else
            Due now
        @endif
    @else
        N/A
    @endif
</div>
                    <div class="priority">
                                @php
                                    $priorityImages = [
                                        'High' => 'priority-high.png',
                                        'Urgent' => 'priority-urgent.png',
                                        'Normal' => 'priority-normal.png',
                                    ];
                                    $priorityImage = isset($priorityImages[$task->priority]) ? $priorityImages[$task->priority] : 'default.png';
                                @endphp
                                <img src="{{ url('public/frontend/images/' . $priorityImage) }}" alt="{{ $task->priority }}">
                                : {{ $task->priority }}
                            </div>
                     <div class="priority">
                    Time spent: {{ gmdate('H:i:s', $task->elapsed_time) }}

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Column for QA tasks -->
    <div class="task-column" id="qa" data-status="QA">
        <div class="todo-add">
        <div class="qa-heading">
                    <img src="{{url ('public/frontend/images/qa.png')}}" alt="">
                    <h3>QA</h3>
                </div>
           
        </div>

        <!-- Task List -->
        <div class="task-list">
            @foreach ($qaTasks as $task)
                <div class="task" draggable="true">
                    <div class="task-name">
                       <a href="{{ route('task.detail', ['id' => $task->id]) }}">
                        <p>{{ $task->name }}</p>
                    </a>
                    </div>
                    <div class="assigne">
                        @if ($task->assignedTo)
                            <img src="{{ url('public/frontend/images/assigned-to.png') }}" alt="" class="assigned-to-icon">
                            : <img src="{{ asset('storage/profilepics/' . $task->assignedTo->profilepic) }}" 
                            alt="{{ $task->assignedTo->username }}'s Profile Picture" class="profile-pic" id="assigned-pic"> 
                        @else
                            <img src="{{ url('public/frontend/images/unassigned.png') }}" alt="Unassigned">
                            by: N/A
                        @endif
                    </div>
                    <div class="due-date" style="margin-top: 4px;">
    <img src="{{ url('public/frontend/images/due-date.png') }}" alt=""> :
    @if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours))
        @if(isset($task->remaining_days) && $task->remaining_days > 0)
            {{ $task->remaining_days }} days {{ $task->remaining_hours }} hours left
        @elseif(isset($task->remaining_hours) && $task->remaining_hours > 0)
            {{ $task->remaining_hours }} hours left
        @elseif(isset($task->overdue_days) && $task->overdue_days > 0)
            Overdue by {{ $task->overdue_days }} days {{ $task->overdue_hours }} hours
        @elseif(isset($task->overdue_hours) && $task->overdue_hours > 0)
            Overdue by {{ $task->overdue_hours }} hours
        @else
            Due now
        @endif
    @else
        N/A
    @endif
</div>
                    <div class="priority">
                                @php
                                    $priorityImages = [
                                        'High' => 'priority-high.png',
                                        'Urgent' => 'priority-urgent.png',
                                        'Normal' => 'priority-normal.png',
                                    ];
                                    $priorityImage = isset($priorityImages[$task->priority]) ? $priorityImages[$task->priority] : 'default.png';
                                @endphp
                                <img src="{{ url('public/frontend/images/' . $priorityImage) }}" alt="{{ $task->priority }}">
                                : {{ $task->priority }}
                            </div>
                     <div class="priority">
                    Time spent: {{ gmdate('H:i:s', $task->elapsed_time) }}

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Column for Completed tasks -->
    <div class="task-column" id="completed" data-status="COMPLETED">
        <div class="todo-add">
        <div class="completed-heading">
                    <img src="{{url ('public/frontend/images/completed.png')}}" alt="">
                    <h3>COMPLETED</h3>
                </div>
            
        </div>

        <!-- Task List -->
        <div class="task-list">
            @foreach ($completedTasks as $task)
                <div class="task" draggable="true">
                    <div class="task-name">
                       <a href="{{ route('task.detail', ['id' => $task->id]) }}">
                        <p>{{ $task->name }}</p>
                    </a>
                    </div>
                    <div class="assigne">
    @if ($task->assignedTo)
        <img src="{{ url('public/frontend/images/assigned-to.png') }}" alt="" class="assigned-to-icon">
        : <img src="{{ asset('storage/profilepics/' . $task->assignedTo->profilepic) }}" 
        alt="{{ $task->assignedTo->username }}'s Profile Picture" class="profile-pic" id="assigned-pic"> 
    @else
        <img src="{{ url('public/frontend/images/unassigned.png') }}" alt="Unassigned">
        by: N/A
    @endif
</div>

<div class="due-date" style="margin-top: 4px;">
    <img src="{{ url('public/frontend/images/due-date.png') }}" alt=""> :
    @if(isset($task->remaining_days) || isset($task->remaining_hours) || isset($task->overdue_days) || isset($task->overdue_hours))
        @if(isset($task->remaining_days) && $task->remaining_days > 0)
            {{ $task->remaining_days }} days {{ $task->remaining_hours }} hours left
        @elseif(isset($task->remaining_hours) && $task->remaining_hours > 0)
            {{ $task->remaining_hours }} hours left
        @elseif(isset($task->overdue_days) && $task->overdue_days > 0)
            Overdue by {{ $task->overdue_days }} days {{ $task->overdue_hours }} hours
        @elseif(isset($task->overdue_hours) && $task->overdue_hours > 0)
            Overdue by {{ $task->overdue_hours }} hours
        @else
            Due now
        @endif
    @else
        N/A
    @endif
</div>
                    <div class="priority">
                        <img src="{{ url('public/frontend/images/priority.png') }}" alt=""> : {{ $task->priority ?? 'Normal' }}
                    </div>
                     <div class="priority">
                    Time spent: {{ gmdate('H:i:s', $task->elapsed_time) }}

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Flatpickr for Start Date
    flatpickr("#start-date", {
        dateFormat: "Y-m-d h:i K",  // Format of the date and time with AM/PM
        allowInput: true,          // Allow manual input
        enableTime: true,          // Enable time selection
        time_24hr: false,          // Use AM/PM format
        onChange: function(selectedDates, dateStr, instance) {
            console.log("Selected start date and time: " + dateStr);
        }
    });

    // Initialize Flatpickr for Due Date
    flatpickr("#due-date", {
        dateFormat: "Y-m-d h:i K",  // Format of the date and time with AM/PM
        allowInput: true,          // Allow manual input
        enableTime: true,          // Enable time selection
        time_24hr: false,          // Use AM/PM format
        onChange: function(selectedDates, dateStr, instance) {
            console.log("Selected due date and time: " + dateStr);
        }
    });
});

// Function to open the "Add Task" modal
function openAddTaskModal(projectID) {
    document.getElementById('project-id').value = projectID; // Set project ID
    console.log('Opening modal for project ID:', projectID); // Debug log

    const modal = document.getElementById('add-task-modal');
    modal.classList.remove('hidden'); // Remove hidden class
    modal.style.display = 'block'; // Ensure display is block

    // Add event listener for outside clicks
    setTimeout(() => document.addEventListener('click', handleOutsideClick), 0);
}

// Function to close the "Add Task" modal
function closeAddTaskModal() {
    const modal = document.getElementById('add-task-modal');
    modal.classList.add('hidden'); // Add hidden class
    modal.style.display = 'none'; // Reset display

    // Remove the outside click listener
    document.removeEventListener('click', handleOutsideClick);
}

// Handle outside click to close the modal
function handleOutsideClick(event) {
    const modal = document.getElementById('add-task-modal');
    const flatpickrElements = document.querySelectorAll('.flatpickr-calendar'); // Flatpickr popups

    // Check if the click is outside the modal and not on any Flatpickr component
    const clickedOnFlatpickr = Array.from(flatpickrElements).some(el => el.contains(event.target));
    if (!modal.contains(event.target) && !event.target.closest('.custom-form') && !clickedOnFlatpickr) {
        closeAddTaskModal();
    }
}





// Function to save the task
function saveTask() {
    const taskName = document.getElementById('task-name').value;
    const assignedTo = document.getElementById('assigned-to').value;
    const dueDate = document.getElementById('due-date').value;
    const priority = document.getElementById('priority').value;
    const projectId = document.getElementById('project-id').value;

    // Validation
    if (!taskName || !assignedTo || !dueDate || !priority) {
        alert('Please fill in all fields!');
        return;
    }

    // Prepare data to send
    const data = {
        name: taskName,
        assigned_to: assignedTo,
        project_id: projectId,
        due_date: dueDate,
        priority: priority,
        _token: '{{ csrf_token() }}', // Include CSRF token for Laravel
    };

    // Send data to server via AJAX
    fetch('{{ route('tasks.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to save task.');
            }
            return response.json();
        })
        .then(task => {
            // Add task to the UI
            const taskList = document.querySelector('.task-list');
            const newTask = `
                <div class="task" draggable="true">
                    <div class="task-name">
                        <a href="">
                            <p>${task.name}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="{{url ('frontend/images/assignedby.png')}}" alt=""> to: ${task.assigned_to_username}
                    </div>
                    <div class="due-date">
                        <img src="{{url ('frontend/images/duedate.png')}}" alt=""> : ${task.due_date}
                    </div>
                    <div class="priority">
                        <img src="{{url ('frontend/images/priority.png')}}" alt=""> : ${task.priority}
                    </div>
                </div>
            `;
            taskList.insertAdjacentHTML('beforeend', newTask);

            // Reset and hide the form
            closeAddTaskModal();
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

// Drag-and-drop functionality
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

</script>

<!-- CSS -->
<style>
#project-details-page {
margin-left: 15px !important;
}
   #add-task-modal {
    display: none; /* Initially hidden */
    z-index: 1000;
    background-color: #fff;
    padding: 15px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
.hidden {
    display: none; /* Add hidden class behavior */
}

    .task-input {
        width: calc(100% - 30px);
        padding: 2px;
        margin: 5px 0;
        border: none;
        background-color: transparent;
        border-radius: 4px;
    }

    .task-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .btn-save-task,
    .btn-cancel-task {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-save-task {
        background-color: #007bff;
        color: white;
    }
     .btn-save-task:hover {
        background-color: #002aee !important;
        color: white;
    }


    .btn-cancel-task {
        background-color: #dc3545;
        color: white;
    }
</style>
@endsection
