
        <div class="users-tasks">
            @php
                $loggedInUser = Auth::user()->username; // Get the logged-in user's username
            @endphp
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
            <div class="{{ strtolower(str_replace(' ', '', $status)) }}-heading">
                <img src="{{ url('frontend/images/' . strtolower(str_replace(' ', '', $status)) . '.png') }}" alt="">
                <h3>{{ $status }}</h3>
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
                                    <img src="{{ url('frontend/images/unassigned.png') }}" alt="Unassigned">
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
   


        <div class="schedule-table">
            <div class="schedule-table-heading">
                <h2>{{ $username }} Today Schedule</h2>
                <form method="GET" action="{{ route('user.dashboard', ['username' => $user->username]) }}">
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
                    @if ($tasks->isEmpty())
                    <tr>
                        <td>{{ $interval }}</td>
                        <td colspan="3">N/A</td>
                    </tr>
                    @else
                    @php
                    $isFirstTask = true;
                    @endphp
                    @foreach($tasks as $task)
                    <tr>
                        <td>{{ $isFirstTask ? $interval : ' " " ' }}</td>
                        <td>{{ $task['task_name'] }}</td>
                        <td>{{ $task['project_name'] }}</td>
                        <td>{{ $task['time_spent'] }}</td>
                    </tr>
                    @php
                    $isFirstTask = false;
                    @endphp
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

