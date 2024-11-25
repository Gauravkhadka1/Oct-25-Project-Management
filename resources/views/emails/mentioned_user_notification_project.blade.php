<!DOCTYPE html>
<html>
<head>
    <title>You Were Mentioned in a Project Task</title>
</head>
<body>
    <h1>You Were Mentioned!</h1>
    <p>Hi {{ $mentionedUser->username }},</p>

    <p>{{ $mentioningUsername }} mentioned you in the task <strong>{{ $taskName }}</strong> under the project <strong>{{ $projectName }}</strong>.</p>

    <p><strong>Comment:</strong> {{ $comment }}</p>

    

    <p>Best regards,<br>Your Team</p>
</body>
</html>
