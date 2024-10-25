<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="forget-password">
    <p>we will send a link to your password. Please click on that link to reset your password</p>
    @if($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
    <!-- <form action="{{route('forget.password.post')}}" method="post"> -->
    <form action="{{ route('forget.password.post') }}" method="post">

        @csrf
    <input type="email" name="email" placeholder="Enter your email here" required>
   
    <button type="submit">Send</button>
    </form>
    </div>
</body>
</html>