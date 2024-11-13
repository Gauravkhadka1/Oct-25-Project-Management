
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
            @endphp

            <!-- Column for To Do tasks -->
            <div class="task-column" id="todo" data-status="To Do">
                <div class="todo-heading">
                    <img src="{{url ('frontend/images/todo.png')}}" alt="">
                    <h3>To Do</h3>
                </div>
                
                <div class="task-list">
                    @foreach ($tasksToDo as $task)
                        <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                            <div class="task-name">
                            <p>{{ $task->name }}</p>
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
                <div class="inprogress-heading">
                    <img src="{{url ('frontend/images/inprogress.png')}}" alt="">
                    <h3>In Progress</h3>
                </div>
             
                <div class="task-list">
                    @foreach ($tasksInProgress as $task)
                        <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                        <div class="task-name">
                            <p>{{ $task->name }}</p>
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
                            <!-- Additional task details here -->
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Column for QA tasks -->
            <div class="task-column" id="qa" data-status="QA">
                <div class="qa-heading">
                    <img src="{{url ('frontend/images/qa.png')}}" alt="">
                    <h3>QA</h3>
                </div>
                <div class="task-list">
                    @foreach ($tasksQA as $task)
                        <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                        <div class="task-name">
                            <p>{{ $task->name }}</p>
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
                            <!-- Additional task details here -->
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Column for Completed tasks -->
            <div class="task-column" id="completed" data-status="Completed">
                <div class="completed-heading">
                    <img src="{{url ('frontend/images/completed.png')}}" alt="">
                    <h3>Completed</h3>
                </div>
                <div class="task-list">
                    @foreach ($tasksCompleted as $task)
                        <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                        <div class="task-name">
                            <p>{{ $task->name }}</p>
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
                            <!-- Additional task details here -->
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Column for Closed tasks -->
            <div class="task-column" id="closed" data-status="Closed">
                <div class="closed-heading">
                    <img src="{{url ('frontend/images/closed.png')}}" alt="">
                    <h3>Closed</h3>
                </div>
                <div class="task-list">
                    @foreach ($tasksClosed as $task)
                        <div class="task" draggable="true" data-task-id="{{ $task->id }}" data-task-type="{{ strtolower($task->category) }}">
                        <div class="task-name">
                            <p>{{ $task->name }}</p>
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
                            <!-- Additional task details here -->
                        </div>
                    @endforeach
                </div>
            </div>
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

