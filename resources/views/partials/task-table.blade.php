
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
<div class="user-tasks">
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
<div class="schedule-table" style="margin-top: 30px;">
    <h2>{{ $username }} today schedule</h2>
    <table class="task-table">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Project Name</th>
                <th>Time Spent</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessionsData as $session)
            <tr>
                <td>{{ $session['task_name'] }}</td>
                <td>{{ $session['project_name'] }}</td>
                <td>{{ $session['time_spent'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</div>

