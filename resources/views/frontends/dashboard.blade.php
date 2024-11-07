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
            
            @if(auth()->check() && in_array(auth()->user()->email, $allowedEmails))
            @foreach(['Sabin', 'Anubhav', 'Lokendra', 'Denisha', 'Muskaan', 'Jeena', 'Sabita', 'Gaurav', 'Sudeep Sir', 'Suraj Sir'] as $username)
                <span class="user-span {{ $username === $loggedInUser ? 'active' : '' }}" data-username="{{ $username }}">
                    {{ $username }}
                </span>
            @endforeach
            @endif
        </div>


        <div class="mytasks">
    <div class="current-tasks">
        <h2>{{ $loggedInUser }} Tasks</h2> 
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
        @endphp

        @if ($hasTasks)
            <table class="task-table">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Task</th>
                        <th>Category Name</th> <!-- New column for Category Name -->
                        <th>Category</th> <!-- New column for Category Type -->
                        <th>Assigned by</th>
                        <th>Start date</th>
                        <th>Due date</th>
                        <th>Priority</th>
                        <th>Actions</th>
                        <th>Timestamp</th>
                        <th>Status</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $serialNo++ }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->category_name }}</td> <!-- Category Name -->
                            <td>{{ $task->category }}</td> <!-- Category Type (Project/Payment/Prospect) -->
                            <td>{{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}</td>
                            <td>{{ $task->start_date }}</td>
                            <td>{{ $task->due_date }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>
                                <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTaskTimer({{ $task->id }})">Start</button>
                            </td>
                            <td id="time-{{ $task->id }}">00:00:00</td>
                            <td>
                                <select name="status">
                                    <option>To Do</option>
                                    <option>In Progress</option>
                                    <option>QA</option>
                                    <option>Completed</option>
                                </select>
                            </td>
                            <td><textarea>{{ $task->comment }}</textarea></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tasks available.</p>
        @endif
    </div>
</div>


    
    
    </div>


    <script>
        

        let timers = {};
        const projectTaskTimers = {};


        // Project Tasks Timer Starts 

        // Load the saved time from the database when the page loads
        window.addEventListener("load", () => {
            @foreach($projects as $project)
            @foreach($project->tasks as $task)
                if (!timers[{{ $task->id }}]) {
                    timers[{{ $task->id }}] = {
                        elapsedTime: {{ $task->elapsed_time * 1000 }},
                        running: false
                    };
                }
                console.log("Timer initialized for task ID:", {{ $task->id }});
                updateProjectTaskTimerDisplay({{ $task->id }});
            @endforeach
            @endforeach
        });

        function toggleProjectTaskTimer(taskId) {
            const timer = timers[taskId];
            const button = document.getElementById(`toggle-${taskId}`);
            
            if (timer) {
                if (timer.running) {
                    // If timer is running, pause it
                    pauseProjectTaskTimer(taskId);
                    button.innerText = "Resume";
                    button.classList.remove("pause");
                    button.classList.add("start");
                } else {
                    // If timer is paused or not started, start/resume it
                    startProjectTaskTimer(taskId);
                    button.innerText = "Pause";
                    button.classList.remove("start");
                    button.classList.add("pause");
                }
            } else {
                console.error(`Timer not found for task ID: ${taskId}`);
            }
        }

        function startProjectTaskTimer(taskId) {
            const timer = timers[taskId];
            if (timer) {
                timer.startTime = new Date().getTime() - timer.elapsedTime;
                timer.running = true;
                
                console.log(`Starting/resuming timer for task ID: ${taskId}`);
                sendProjectTaskTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/start-timer`);
                
                updateProjectTaskTimer(taskId);
            }
        }

        function pauseProjectTaskTimer(taskId) {
            const timer = timers[taskId];
            if (timer && timer.running) {
                timer.elapsedTime = new Date().getTime() - timer.startTime;
                timer.running = false;
                
                console.log(`Pausing timer for task ${taskId}, elapsed time: ${timer.elapsedTime}`);
                
                sendProjectTaskTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/pause-timer`);
            }
        }

        function updateProjectTaskTimer(taskId) {
            const timer = timers[taskId];
            if (timer && timer.running) {
                const currentTime = new Date().getTime() - timer.startTime;
                timer.elapsedTime = currentTime;
                
                updateProjectTaskTimerDisplay(taskId);
                
                setTimeout(() => updateProjectTaskTimer(taskId), 1000);
            }
        }

        function updateProjectTaskTimerDisplay(taskId) {
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

        function sendProjectTaskTimerUpdate(taskId, elapsedTime, url) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ elapsed_time: elapsedTime })
            })
            .then(response => response.json())
            .then(data => console.log(`Timer updated: ${data.message}`))
            .catch(error => console.error('Error updating timer:', error));
        }

   // Project Task Timer Ends 
//Prospect Task Timer Starts
let prospectTaskTimers = {};
function toggleProspectTaskTimer(prospectTaskId) {
    const timer = prospectTaskTimers[prospectTaskId];
    const button = document.getElementById(`toggle-prospect-${prospectTaskId}`);
    
    if (timer) {
        if (timer.running) {
            pauseProspectTaskTimer(prospectTaskId);
            button.innerText = "Resume";
            button.classList.remove("pause");
            button.classList.add("start");
        } else {
            startProspectTaskTimer(prospectTaskId);
            button.innerText = "Pause";
            button.classList.remove("start");
            button.classList.add("pause");
        }
    } else {
        console.error(`Timer not found for prospect task ID: ${prospectTaskId}`);
    }
}

function startProspectTaskTimer(prospectTaskId) {
    const timer = prospectTaskTimers[prospectTaskId] || { elapsedTime: 0, running: false };
    timer.startTime = new Date().getTime() - timer.elapsedTime;
    timer.running = true;
    prospectTaskTimers[prospectTaskId] = timer;
    sendProspectTaskTimerUpdate(prospectTaskId, timer.elapsedTime / 1000, `/prospect_tasks/${prospectTaskId}/start-timer`);
    updateProspectTaskTimer(prospectTaskId);
}

function pauseProspectTaskTimer(prospectTaskId) {
    const timer = prospectTaskTimers[prospectTaskId];
    if (timer && timer.running) {
        timer.elapsedTime = new Date().getTime() - timer.startTime;
        timer.running = false;
        sendProspectTaskTimerUpdate(prospectTaskId, timer.elapsedTime / 1000, `/prospect_tasks/${prospectTaskId}/pause-timer`);
    }
}

function updateProspectTaskTimer(prospectTaskId) {
    const timer = prospectTaskTimers[prospectTaskId];
    if (timer && timer.running) {
        timer.elapsedTime = new Date().getTime() - timer.startTime;
        updateProspectTaskTimerDisplay(prospectTaskId);
        setTimeout(() => updateProspectTaskTimer(prospectTaskId), 1000);
    }
}

function updateProspectTaskTimerDisplay(prospectTaskId) {
    const timer = prospectTaskTimers[prospectTaskId];
    if (timer) {
        const totalSeconds = Math.floor(timer.elapsedTime / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        document.getElementById(`prospect-time-${prospectTaskId}`).innerText = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
}

function sendProspectTaskTimerUpdate(prospectTaskId, elapsedTime, url) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ elapsed_time: elapsedTime })
    })
    .then(response => response.json())
    .then(data => console.log(`Prospect Task timer updated: ${data.message}`))
    .catch(error => console.error('Error updating prospect task timer:', error));
}


      
   // Prospect Task Timer Ends 
       
   document.addEventListener("DOMContentLoaded", function() {
    const userSpans = document.querySelectorAll('.user-span');

    userSpans.forEach(span => {
        span.addEventListener('click', function() {
            // Remove 'active' class from all spans
            userSpans.forEach(s => s.classList.remove('active'));
            
            // Add 'active' class to the clicked span
            this.classList.add('active');

            const selectedUsername = this.dataset.username;
            showTasksForUser(selectedUsername);
        });
    });

    function showTasksForUser(username) {
        // Here you would typically make an AJAX call to fetch tasks for the selected user
        fetch(`/tasks?username=${username}`)
            .then(response => response.json())
            .then(tasks => {
                updateTaskDetails(tasks, username);
            })
            .catch(error => console.error('Error fetching tasks:', error));
    }

    function updateTaskDetails(tasks, username) {
    const tasksContainer = document.querySelector('.current-tasks');
    tasksContainer.innerHTML = `<h2>${username} Tasks</h2>`; // Update the header with the selected username

    if (tasks.length > 0) {
        let tableHtml = `<table class="task-table">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Task</th>
                    <th>Category Name</th>
                    <th>Category</th>
                    <th>Assigned by</th>
                    <th>Start date</th>
                    <th>Due date</th>
                    <th>Priority</th>
                    <th>Actions</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>`;

        tasks.forEach((task, index) => {
            tableHtml += `<tr>
                <td>${index + 1}</td>
                <td>${task.name}</td>
                <td>${task.category_name || 'N/A'}</td> <!-- Category Name -->
                <td>${task.category || 'N/A'}</td> <!-- Category Type -->
                <td>${task.assignedBy || 'N/A'}</td> <!-- Assigned By -->
                <td>${task.start_date}</td>
                <td>${task.due_date}</td>
                <td>${task.priority}</td>
                <td>
                    <button class="btn-toggle start" id="toggle-${task.id}" onclick="toggleTaskTimer(${task.id})">Start</button>
                </td>
                <td id="time-${task.id}">00:00:00</td>
                <td>
                    <select name="status">
                        <option>To Do</option>
                        <option>In Progress</option>
                        <option>QA</option>
                        <option>Completed</option>
                    </select>
                </td>
                <td><textarea>${task.comment}</textarea></td>
            </tr>`;
        });

        tableHtml += `</tbody></table>`;
        tasksContainer.innerHTML += tableHtml;
    } else {
        tasksContainer.innerHTML += `<p>No tasks available.</p>`;
    }
}


});



    </script>
</main>
@endsection
