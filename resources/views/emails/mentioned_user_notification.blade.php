<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You were mentioned!</title>
</head>
<body>
<p>Hello {{ $mentionedUsername }},</p>
<p>{{ $mentioningUsername }} mentioned you in a comment in the company: {{ $companyName }}.</p>
<p>Comment: {{ $activityDetails }}</p>

<!-- <p><a href="{{ $activityLink }}">View Activity</a></p> -->

</body>
</html>
