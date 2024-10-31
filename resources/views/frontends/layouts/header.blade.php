<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Project Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.atwho/1.3.0/css/jquery.atwho.min.css" />
  <link rel="stylesheet" href="{{url('frontend/css/index.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/dashboard.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/prospects.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/projects.css')}}">

  @php
  $allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
  $user = auth()->user();
  $userEmail = $user->email;
  $username = $user->username;
  @endphp
</head>

<body>
  <div class="wrapper">
    <header>
      <nav>
        <ul class="horizontal-menu">
          <div class="start">
            <div class="h-menu">
              <img src="{{url('frontend/images/hamburger.png')}}" class="menu-icon" onclick="toggleSidebar()">
            </div>
            <div class="greetings">
              <p>Good Morning, {{ $username }}</p>
            </div>
          </div>
          <div class="end">
            <div class="notification">
              <img src="{{url('frontend/images/notification.png')}}" alt="" class="notification-icon">
            </div>
            <div class="profile-header">
              <a href="{{url('/dashboard')}}">
                <div class="user-icon">
                  <img src="{{url('frontend/images/user.png')}}" alt="">
                </div>
                <div class="name">{{ $username }}</div>
              </a>
            </div>
          </div>
        </ul>
      </nav>
    </header>

    <!-- Sidebar Menu -->
    <div id="sidebar" class="sidebar">
      <ul>
        <li><a href="{{url('/dashboard')}}"> <img src="{{url('frontend/images/wtn.png')}}" class="logo-img"></a></li>
        <li><a href="{{url('/projects')}}">Payments</a></li>
        <li><a href="{{url('/projects')}}">Renewals</a></li>
        <li><a href="{{url('/projects')}}">Projects</a></li>
        <li><a href="{{url('/projects')}}">Tasks</a></li>
        <li><a href="{{url('/projects')}}">Clients</a></li>
      </ul>
    </div>
  </div>


  <script>
    function toggleSidebar() {
      document.body.classList.toggle("sidebar-hidden");
    }
  </script>
</body>
</html>


