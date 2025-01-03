@extends ('frontends.layouts.main')
@section ('main-container')

@php
$allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
$user = auth()->user();
$username = $user->username;
@endphp

<div class="new-dashboard-page">

    <div class="users-tasks">
        @php
        $loggedInUser = Auth::user()->username; // Get the logged-in user's username
        @endphp
       <p>{{$loggedInUser}} Tasks</p> 

    </div>

    <div class="task-board">
        @php
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
@endphp


        <!-- Column for To Do tasks -->
        <div class="task-column" id="todo" data-status="To Do">
            <h3>To Do</h3>
            <div class="task-list">
                @foreach ($tasksToDo as $task)
                    <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                        <div class="task-name">
                        {{ $task->name }}
                        </div>
                        <div class="in-project">
                        in {{ $task->category_name }}
                        </div>
                        <div class="assigne">
                            Assigned by: {{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}
                        </div>
                        <div class="due-date">
                         Due Date:{{ $task->due_date }}
                        </div>
                        <div class="priority">
                        Priority: {{ $task->priority }}
                        </div>
                        <div class="time-details">
                            <div class="start-pause">
                            <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }}, '{{ $task->category }}')"><img src="{{url ('frontend/images/play.png')}}" alt=""></button>
                            </div>
                            <div class="time-data"id="time-{{ $task->id }}">00:00:00
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Column for In Progress tasks -->
        <div class="task-column" id="inprogress" data-status="In Progress">
            <h3>In Progress</h3>
            <div class="task-list">
                @foreach ($tasksInProgress as $task)
                    <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                    <div class="task-name">
                        {{ $task->name }}
                        </div>
                        <div class="in-project">
                        in {{ $task->category_name }}
                        </div>
                        <div class="assigne">
                            Assigned by: {{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}
                        </div>
                        <div class="due-date">
                         Due Date:{{ $task->due_date }}
                        </div>
                        <div class="priority">
                        Priority: {{ $task->priority }}
                        </div>
                        <div class="start-pause">
                        <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }}, '{{ $task->category }}')">Start</button>
                        </div>
                        <div class="time-data"id="time-{{ $task->id }}">00:00:00
                        </div>
                        <!-- Additional task details here -->
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Column for QA tasks -->
        <div class="task-column" id="qa" data-status="QA">
            <h3>QA</h3>
            <div class="task-list">
                @foreach ($tasksQA as $task)
                    <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                    <div class="task-name">
                        {{ $task->name }}
                        </div>
                        <div class="in-project">
                        in {{ $task->category_name }}
                        </div>
                        <div class="assigne">
                            Assigned by: {{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}
                        </div>
                        <div class="due-date">
                         Due Date:{{ $task->due_date }}
                        </div>
                        <div class="priority">
                        Priority: {{ $task->priority }}
                        </div>
                        <div class="start-pause">
                        <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }}, '{{ $task->category }}')">Start</button>
                        </div>
                        <div class="time-data"id="time-{{ $task->id }}">00:00:00
                        </div>
                        <!-- Additional task details here -->
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Column for Completed tasks -->
        <div class="task-column" id="completed" data-status="Completed">
            <h3>Completed</h3>
            <div class="task-list">
                @foreach ($tasksCompleted as $task)
                    <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                    <div class="task-name">
                        {{ $task->name }}
                        </div>
                        <div class="in-project">
                        in {{ $task->category_name }}
                        </div>
                        <div class="assigne">
                            Assigned by: {{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}
                        </div>
                        <div class="due-date">
                         Due Date:{{ $task->due_date }}
                        </div>
                        <div class="priority">
                        Priority: {{ $task->priority }}
                        </div>
                        <div class="start-pause">
                        <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }}, '{{ $task->category }}')">Start</button>
                        </div>
                        <div class="time-data"id="time-{{ $task->id }}">00:00:00
                        </div>
                        <!-- Additional task details here -->
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Column for Closed tasks -->
        <div class="task-column" id="closed" data-status="Closed">
            <h3>Closed</h3>
            <div class="task-list">
                @foreach ($tasksClosed as $task)
                    <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                    <div class="task-name">
                        {{ $task->name }}
                        </div>
                        <div class="in-project">
                        in {{ $task->category_name }}
                        </div>
                        <div class="assigne">
                            Assigned by: {{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}
                        </div>
                        <div class="due-date">
                         Due Date:{{ $task->due_date }}
                        </div>
                        <div class="priority">
                        Priority: {{ $task->priority }}
                        </div>
                        <div class="start-pause">
                        <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }}, '{{ $task->category }}')">Start</button>
                        </div>
                        <div class="time-data"id="time-{{ $task->id }}">00:00:00
                        </div>
                        <!-- Additional task details here -->
                    </div>
                @endforeach
                <td>
                    @if ($task->category == 'Project')
                        <textarea name="comment[{{ $task->id }}]" class="task-comment">{{ $task->comment }}</textarea>
                    @elseif ($task->category == 'Payment')
                        <textarea name="comment[{{ $task->id }}]" class="payment-task-comment">{{ $task->comment }}</textarea>
                    @elseif ($task->category == 'Prospect')
                        <textarea name="comment[{{ $task->id }}]" class="prospect-task-comment">{{ $task->comment }}</textarea>
                    @endif
                </td>
            </div>
        </div>
    </div>
   
</div>

<script>
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


    // time script 
    
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
    @foreach($projects as $project)
        @foreach($project->tasks as $task)
            timers[{{ $task->id }}] = {
                elapsedTime: {{ $task->elapsed_time * 1000 }},
                running: false
            };
            console.log("Timer initialized for project task ID:", {{ $task->id }});
            updateTimerDisplay({{ $task->id }});
        @endforeach
    @endforeach

    // Initialize timers for Payment tasks
    @foreach($payments as $payment)
        @foreach($payment->payment_tasks as $task)
            timers[{{ $task->id }}] = {
                elapsedTime: {{ $task->elapsed_time * 1000 }},
                running: false
            };
            console.log("Timer initialized for payment task ID:", {{ $task->id }});
            updateTimerDisplay({{ $task->id }});
        @endforeach
    @endforeach

    // Initialize timers for Prospect tasks
    @foreach($prospects as $prospect)
        @foreach($prospect->prospect_tasks as $task)
            timers[{{ $task->id }}] = {
                elapsedTime: {{ $task->elapsed_time * 1000 }},
                running: false
            };
            console.log("Timer initialized for prospect task ID:", {{ $task->id }});
            updateTimerDisplay({{ $task->id }});
        @endforeach
    @endforeach
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
document.querySelectorAll('.task-status, .payment-task-status, .prospect-task-status').forEach(statusElement => {
    statusElement.addEventListener('change', function () {
        const taskId = this.name.match(/\d+/)[0];
        const status = this.value;
        const taskType = this.classList.contains('payment-task-status') ? 'payment' : 
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
       
   


    </script>
</script>
@endsection