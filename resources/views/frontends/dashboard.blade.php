@extends('frontends.layouts.main')

@section('main-container')
<main>
    <div class="profile-page">
    <div class="users-tasks">
            @php
                $loggedInUser = Auth::user()->username; // Get the logged-in user's username
            @endphp

            @foreach(['Sabin', 'Anubhav', 'Lokendra', 'Denisha', 'Muskaan', 'Jeena', 'Sabita', 'Gaurav', 'Sudeep Sir', 'Suraj Sir'] as $username)
                <span class="user-span {{ $username === $loggedInUser ? 'active' : '' }}" data-username="{{ $username }}">
                    {{ $username }}
                </span>
            @endforeach
        </div>


        <div class="mytasks">
            <div class="current-tasks">
            <h2>{{ $loggedInUser }} Tasks</h2> 
                @php
                    $hasTasks = false; // Flag to check if there are any tasks
                    $serialNo = 1;
                @endphp

                @forelse($projects as $project)
                    @if(!$project->tasks->isEmpty())
                        @if (!$hasTasks) 
                            <table class="task-table">
                                <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Task</th>
                                        <th>Project</th>
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
                        @endif
                        
                        @foreach($project->tasks as $index => $task)
                            @php $hasTasks = true; @endphp
                            <tr>
                                <td>{{ $serialNo++ }}</td> 
                                <td>{{ $task->name }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}</td>
                                <td>{{ $task->start_date }}</td>
                                <td>{{ $task->due_date }}</td>
                                <td>{{ $task->priority }}</td>
                                <td>
                                    <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }})">Start</button>
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

                        @if (!$hasTasks) 
                            </tbody>
                            </table>
                        @endif
                    @endif
                @empty
                    <p>No projects available.</p>
                @endforelse

                @if ($hasTasks)
                    </tbody>
                    </table>
                @endif

            </div>
        
        </div>
    
    </div>


    <script>
        let timers = {};

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
                updateTimerDisplay({{ $task->id }});
            @endforeach
            @endforeach
        });

        function toggleTimer(taskId) {
            const timer = timers[taskId];
            const button = document.getElementById(`toggle-${taskId}`);
            
            if (timer) {
                if (timer.running) {
                    // If timer is running, pause it
                    pauseTimer(taskId);
                    button.innerText = "Resume";
                    button.classList.remove("pause");
                    button.classList.add("start");
                } else {
                    // If timer is paused or not started, start/resume it
                    startTimer(taskId);
                    button.innerText = "Pause";
                    button.classList.remove("start");
                    button.classList.add("pause");
                }
            } else {
                console.error(`Timer not found for task ID: ${taskId}`);
            }
        }

        function startTimer(taskId) {
            const timer = timers[taskId];
            if (timer) {
                timer.startTime = new Date().getTime() - timer.elapsedTime;
                timer.running = true;
                
                console.log(`Starting/resuming timer for task ID: ${taskId}`);
                sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/start-timer`);
                
                updateTimer(taskId);
            }
        }

        function pauseTimer(taskId) {
            const timer = timers[taskId];
            if (timer && timer.running) {
                timer.elapsedTime = new Date().getTime() - timer.startTime;
                timer.running = false;
                
                console.log(`Pausing timer for task ${taskId}, elapsed time: ${timer.elapsedTime}`);
                
                sendTimerUpdate(taskId, timer.elapsedTime / 1000, `/tasks/${taskId}/pause-timer`);
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

        function sendTimerUpdate(taskId, elapsedTime, url) {
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
                            <th>Project</th>
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
                        <td>${task.project_name}</td>
                        <td>${task.assignedBy ? task.assignedBy.username : 'N/A'}</td>
                        <td>${task.start_date}</td>
                        <td>${task.due_date}</td>
                        <td>${task.priority}</td>
                        <td>
                            <button class="btn-toggle start" id="toggle-${task.id}" onclick="toggleTimer(${task.id})">Start</button>
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
                tasksContainer.innerHTML += tableHtml; // Append the table to the tasks container
            } else {
                tasksContainer.innerHTML += `<p>No tasks available for ${username}.</p>`;
            }
        }
    });


    </script>
</main>
@endsection
