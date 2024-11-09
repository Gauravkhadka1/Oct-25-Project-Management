<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<p>A new comment has been added to task "{{ $taskName }}":</p>
<p>{{ $comment }}</p>
<p>Click the link below to view the task:</p>
<a href="{{ $taskUrl }}">View Task</a>

</body>
</html>