<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You were mentioned!</title>
</head>
<body>
    <p>Hello {{ $username }},</p>
    <p>You were mentioned in the following activity:</p>
    <blockquote>{{ $activityDetails }}</blockquote>
    <p>Click the link below to view the full activity:</p>
    <p><a href="{{ $activityLink }}">View Activity</a></p>
</body>
</html>
