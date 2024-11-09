<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned</title>
</head>
<body>
    <h1>You have been assigned a new task: {{ $taskName }}</h1>
    <p>Assigned by: {{ $assignedBy }}</p>
    <p>Due date: {{ $dueDate }}</p>
</body>
</html>