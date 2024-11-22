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
<div class="task-board">
    <!-- Column for To Do tasks -->
    <div class="task-column" id="todo" data-status="todo">
        <div class="todo-add">
        <div class="todo-heading">
                    <img src="{{url ('frontend/images/todo.png')}}" alt="">
                    <h3>To Do</h3>
                </div>
            <div class="add-icon">
                <button class="btn-create-new" id="task-create" onclick="openAddTaskModal({{ $project->id }})">
                    <img src="{{ url('frontend/images/add-new.png') }}" alt="">
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
                        <a href="">
                            <p>{{ $task->name }}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="{{ url('frontend/images/assignedby.png') }}" alt="">
                        to: {{ $task->assignedUser->username ?? 'Unassigned' }}
                    </div>
                    <div class="due-date">
                        <img src="{{ url('frontend/images/duedate.png') }}" alt=""> : {{ $task->due_date ?? 'No Due Date' }}
                    </div>
                    <div class="priority">
                        <img src="{{ url('frontend/images/priority.png') }}" alt=""> : {{ $task->priority ?? 'Normal' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Column for In Progress tasks -->
    <div class="task-column" id="in-progress" data-status="in-progress">
        <div class="todo-add">
        <div class="inprogress-heading">
                    <img src="{{url ('frontend/images/inprogress.png')}}" alt="">
                    <h3>In Progress</h3>
                </div>
            
        </div>

        <!-- Task List -->
        <div class="task-list">
            @foreach ($inProgressTasks as $task)
                <div class="task" draggable="true">
                    <div class="task-name">
                        <a href="">
                            <p>{{ $task->name }}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="{{ url('frontend/images/assignedby.png') }}" alt="">
                        to: {{ $task->assignedUser->username ?? 'Unassigned' }}
                    </div>
                    <div class="due-date">
                        <img src="{{ url('frontend/images/duedate.png') }}" alt=""> : {{ $task->due_date ?? 'No Due Date' }}
                    </div>
                    <div class="priority">
                        <img src="{{ url('frontend/images/priority.png') }}" alt=""> : {{ $task->priority ?? 'Normal' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Column for QA tasks -->
    <div class="task-column" id="qa" data-status="qa">
        <div class="todo-add">
        <div class="qa-heading">
                    <img src="{{url ('frontend/images/qa.png')}}" alt="">
                    <h3>QA</h3>
                </div>
           
        </div>

        <!-- Task List -->
        <div class="task-list">
            @foreach ($qaTasks as $task)
                <div class="task" draggable="true">
                    <div class="task-name">
                        <a href="">
                            <p>{{ $task->name }}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="{{ url('frontend/images/assignedby.png') }}" alt="">
                        to: {{ $task->assignedUser->username ?? 'Unassigned' }}
                    </div>
                    <div class="due-date">
                        <img src="{{ url('frontend/images/duedate.png') }}" alt=""> : {{ $task->due_date ?? 'No Due Date' }}
                    </div>
                    <div class="priority">
                        <img src="{{ url('frontend/images/priority.png') }}" alt=""> : {{ $task->priority ?? 'Normal' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Column for Completed tasks -->
    <div class="task-column" id="completed" data-status="completed">
        <div class="todo-add">
        <div class="completed-heading">
                    <img src="{{url ('frontend/images/completed.png')}}" alt="">
                    <h3>Completed</h3>
                </div>
            
        </div>

        <!-- Task List -->
        <div class="task-list">
            @foreach ($completedTasks as $task)
                <div class="task" draggable="true">
                    <div class="task-name">
                        <a href="">
                            <p>{{ $task->name }}</p>
                        </a>
                    </div>
                    <div class="assigne">
                        <img src="{{ url('frontend/images/assignedby.png') }}" alt="">
                        to: {{ $task->assignedUser->username ?? 'Unassigned' }}
                    </div>
                    <div class="due-date">
                        <img src="{{ url('frontend/images/duedate.png') }}" alt=""> : {{ $task->due_date ?? 'No Due Date' }}
                    </div>
                    <div class="priority">
                        <img src="{{ url('frontend/images/priority.png') }}" alt=""> : {{ $task->priority ?? 'Normal' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<!-- JavaScript -->
<script>
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
    if (!modal.contains(event.target) && !event.target.closest('.custom-form')) {
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

    .btn-cancel-task {
        background-color: #dc3545;
        color: white;
    }
</style>
@endsection
