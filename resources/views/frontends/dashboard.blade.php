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
                            <td>
    <span class="{{ $task->category === 'Project' ? 'label-project' : ($task->category === 'Payment' ? 'label-payment' : 'label-prospect') }}">
        {{ $task->category }}
    </span>
</td>
 <!-- Category Type (Project/Payment/Prospect) -->
                            <td>{{ $task->assignedBy ? $task->assignedBy->username : 'N/A' }}</td>
                            <td>{{ $task->start_date }}</td>
                            <td>{{ $task->due_date }}</td>
                            <td>{{ $task->priority }}</td>
                            <td>
                                    <button class="btn-toggle start" id="toggle-{{ $task->id }}" onclick="toggleTimer({{ $task->id }})">Start</button>
                                </td>
                                <td id="time-{{ $task->id }}">00:00:00</td>
                                <td>
                                    <select name="status[{{ $task->id }}]" class="task-status">
                                        <option {{ $task->status === 'To Do' ? 'selected' : '' }}>To Do</option>
                                        <option {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option {{ $task->status === 'QA' ? 'selected' : '' }}>QA</option>
                                        <option {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="comment[{{ $task->id }}]" class="task-comment">{{ $task->comment }}</textarea>
                                </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tasks available.</p>
        @endif
    </div>
   
</div>
<div class="schedule-table">
    <div class="schedule-table-heading">
        <h2>{{ $loggedInUser }}'s Today's Schedule</h2>
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
                @if ($tasks->isEmpty())
                    <tr>
                        <td>{{ $interval }}</td>
                        <td colspan="3">N/A</td>
                    </tr>
                @else
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $interval }}</td>
                            <td>{{ $task['task_name'] }}</td>
                            <td>{{ $task['project_name'] }}</td>
                            <td>{{ $task['time_spent'] }}</td>
                        </tr>
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
        </tfoot>
    </table>
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
        document.querySelectorAll('.task-status').forEach(statusElement => {
    statusElement.addEventListener('change', function () {
        const taskId = this.name.match(/\d+/)[0];
        const status = this.value;

        fetch(`/tasks/update-status-comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ taskId, status })
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

document.querySelectorAll('.task-comment').forEach(commentElement => {
    commentElement.addEventListener('change', function () {
        const taskId = this.name.match(/\d+/)[0];
        const comment = this.value;

        fetch(`/tasks/update-comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ task_id: taskId, comment })
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
</main>
@endsection
