<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div>
    @foreach($activity->replies as $reply)
        <div class="reply">
            <strong>{{ $reply->user->username }}:</strong> {{ $reply->reply }}
        </div>
    @endforeach
</div>


</body>
</html>