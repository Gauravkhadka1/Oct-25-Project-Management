@extends('frontends.layouts.main')

@section('main-container')
<main>
    <div class="profile-page"> 
        <div class="profile-name">
            Good Morning, <br>{{ $username }}
        </div>  
    </div>
    <div class="mytasks">
        <div class="current-tasks">
            <h2>Current Tasks</h2>
            @php
                $hasTasks = false; // Flag to check if there are any tasks
            @endphp

            @forelse($projects as $project)
                @if(!$project->tasks->isEmpty())
                    @if (!$hasTasks) 
                        <table class="task-table">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                            <td>{{ $index + 1 }}</td>
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
                                    <option>Set Status</option>
                                    <!-- Add more options here -->
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
        @if(Auth::check() && Auth::user()->email == $user->email)
            <div class="edit-logout">
                <div class="edit-profile">
                    <a href="{{ route('profile.edit') }}">Edit Profile</a>
                </div>
                <div class="logout">
                    <a href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        @endif
    </div>

    <!-- ----- My Schedule  ----  -->
    <div class="myschedule"> 
        <div class="schedule-heading">
            <h2>My Today Schedule</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Task</th>
                    <th>Min</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>10:00 - 10:30 AM</td>
                    <td>Design</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>10:30 - 11:00 AM</td>
                    <td>Development</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>11:00 - 11:30 AM</td>
                    <td>Testing</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>11:30 - 12:00 PM</td>
                    <td>Code Review</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>12:00 - 12:30 PM</td>
                    <td>Meeting</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>12:30 - 1:00 PM</td>
                    <td>Documentation</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>1:00 - 1:30 PM</td>
                    <td>Lunch Break</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>1:30 - 2:00 PM</td>
                    <td>Design Review</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>2:00 - 2:30 PM</td>
                    <td>Client Call</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>2:30 - 3:00 PM</td>
                    <td>Feature Development</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>3:00 - 3:30 PM</td>
                    <td>Bug Fixing</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>3:30 - 4:00 PM</td>
                    <td>Testing</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>4:00 - 4:30 PM</td>
                    <td>Refactoring Code</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>4:30 - 5:00 PM</td>
                    <td>Prepare Presentation</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>5:00 - 5:30 PM</td>
                    <td>Team Sync</td>
                    <td>30 min</td>
                </tr>
                <tr>
                    <td>5:30 - 6:00 PM</td>
                    <td>Wrap Up Tasks</td>
                    <td>30 min</td>
                </tr>
            </tbody>
        </table>
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
    </script>
</main>
@endsection
