<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<p>The status of task "{{ $taskName }}" has been updated to "{{ $status }}".</p>
<p>Click the link below to view the task:</p>
<a href="{{ $taskUrl }}">View Task</a>

</body>
</html>