@extends ('frontends.layout.main')
@section ('main-container')
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
            <h3>New</h3>
        </div>

        <div class="task-list">
            @foreach ($prospects as $key => $prospect)
            <div class="task" draggable="true" data-task-id="{{ $prospect->id }}" data-task-type="{{ strtolower($prospect->category) }}">
                <div class="task-name">
                    <p>{{ $prospect->name }}</p>
                </div>
                <div class="in-project">
                    in {{ $prospect->category_name }}
                </div>

                <div class="due-date">
                    <img src="{{url ('frontend/images/duedate.png')}}" alt=""> : {{ $prospect->inquiry_date }}
                </div>
                <div class="priority">
                    <img src="{{url ('frontend/images/priority.png')}}" alt="">: {{ $prospect->probability }}
                </div>
            </div>
            @endforeach
        </div>
    </div>


    <!-- Column for In Progress tasks -->
    <div class="task-column" id="inprogress" data-status="In Progress">
        <div class="inprogress-heading">
            <img src="{{url ('frontend/images/inprogress.png')}}" alt="">
            <h3>Dealing</h3>
        </div>

        <div class="task-list">
            @foreach ($prospects as $key => $prospect)
            <div class="task" draggable="true" data-task-id="{{ $prospect->id }}" data-task-type="{{ strtolower($prospect->category) }}">
                <div class="task-name">
                    <p>{{ $prospect->name }}</p>
                </div>
                <div class="in-project">
                    in {{ $prospect->category_name }}
                </div>

                <div class="due-date">
                    <img src="{{url ('frontend/images/duedate.png')}}" alt=""> : {{ $prospect->inquiry_date }}
                </div>
                <div class="priority">
                    <img src="{{url ('frontend/images/priority.png')}}" alt="">: {{ $prospect->probability }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Column for QA tasks -->
    <div class="task-column" id="qa" data-status="QA">
        <div class="qa-heading">
            <img src="{{url ('frontend/images/qa.png')}}" alt="">
            <h3>Quote Sent</h3>
        </div>
        <div class="task-list">
            @foreach ($prospects as $key => $prospect)
            <div class="task" draggable="true" data-task-id="{{ $prospect->id }}" data-task-type="{{ strtolower($prospect->category) }}">
                <div class="task-name">
                    <p>{{ $prospect->name }}</p>
                </div>
                <div class="in-project">
                    in {{ $prospect->category_name }}
                </div>

                <div class="due-date">
                    <img src="{{url ('frontend/images/duedate.png')}}" alt=""> : {{ $prospect->inquiry_date }}
                </div>
                <div class="priority">
                    <img src="{{url ('frontend/images/priority.png')}}" alt="">: {{ $prospect->probability }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Column for Completed tasks -->
    <div class="task-column" id="completed" data-status="Completed">
        <div class="completed-heading">
            <img src="{{url ('frontend/images/completed.png')}}" alt="">
            <h3>Agreement Sent</h3>
        </div>
        <div class="task-list">
            @foreach ($prospects as $key => $prospect)
            <div class="task" draggable="true" data-task-id="{{ $prospect->id }}" data-task-type="{{ strtolower($prospect->category) }}">
                <div class="task-name">
                    <p>{{ $prospect->name }}</p>
                </div>
                <div class="in-project">
                    in {{ $prospect->category_name }}
                </div>

                <div class="due-date">
                    <img src="{{url ('frontend/images/duedate.png')}}" alt=""> : {{ $prospect->inquiry_date }}
                </div>
                <div class="priority">
                    <img src="{{url ('frontend/images/priority.png')}}" alt="">: {{ $prospect->probability }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection