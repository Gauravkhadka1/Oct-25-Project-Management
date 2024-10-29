<!-- resources/views/emails/mention.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>You Were Mentioned</title>
</head>
<body>
    <h1>Hello {{ $username }},</h1>
    <p>You were mentioned in a message:</p>
    <blockquote>
        {{ $message }}
    </blockquote>
    <p>Best regards,<br>Your Application</p>
</body>
</html>
