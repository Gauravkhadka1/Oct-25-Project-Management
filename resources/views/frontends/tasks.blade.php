<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Tasks</title>
</head>
<body>
    <h1>Tasks for Project ID: {{ $projectId }}</h1>
    @if($tasks->isEmpty())
        <p>No tasks found for this project.</p>
    @else
        <ul>
            @foreach($tasks as $task)
                <li>{{ $task->name }} - {{ $task->priority }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>