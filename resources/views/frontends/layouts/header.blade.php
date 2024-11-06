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
  <link rel="stylesheet" href="{{url('frontend/css/payments.css')}}">


  @php
  $allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
  $user = auth()->user();
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
              <p id="greeting"></p>
            </div>
          </div>
          <div class="end">
            <div class="notification">
              <img src="{{url('frontend/images/notification.png')}}" alt="" class="notification-icon">
            </div>
            <div class="profile-header" style="position: relative;">
              <a href="javascript:void(0);" onclick="toggleDropdown(event)">
                <div class="user-icon">
                  <img src="{{url('frontend/images/user.png')}}" alt="User Icon">
                </div>
                <div class="name">
                  <p>{{ $username }}</p>
                </div>
                <div class="dropdown-icon"><img src="{{url('frontend/images/dropdown-arrow.png')}}" alt=""></div>
              </a>
              <div id="dropdownMenu" class="dropdown-menu">
                <a href="{{ route('dashboard') }}"><img src="{{url('frontend/images/dashboard.png')}}" alt=""> Dashboard</a>
                <a href="{{ route('logout') }}"><img src="{{url('frontend/images/logout.png')}}" alt="">Logout</a>
              </div>
            </div>
          </div>
        </ul>
      </nav>
    </header>

    <!-- Sidebar Menu -->
    <div id="sidebar" class="sidebar">
      <ul>
        <li><a href="{{url('/dashboard')}}"> <img src="{{url('frontend/images/wtn-logo-black.svg')}}" class="logo-img"></a></li>
        @if(auth()->check() && in_array(auth()->user()->email, $allowedEmails))
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
            <div class="icon-text">
              <img src="{{url('frontend/images/time.png')}}" alt=""> Payments
            </div>
            <div class="dropdown-arrow-div">
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
          </a>
          <ul class="task-dropdown">
            <li><a href="{{url('/payments')}}">All Payments</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
            <div class="icon-text">
              <img src="{{url('frontend/images/renewable.png')}}" alt=""> Renewals
            </div>
            <div class="dropdown-arrow-div">
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>

          </a>
          <ul class="task-dropdown">
            <li><a href="{{url('/tasks/all')}}">All</a></li>
            <li><a href="{{url('/tasks/all')}}">35 days left</a></li>
            <li><a href="{{url('/tasks/my')}}">30 days</a></li>
            <li><a href="{{url('/tasks/my')}}">15 days</a></li>
            <li><a href="{{url('/tasks/my')}}">7 days</a></li>
            <li><a href="{{url('/tasks/my')}}">3 days</a></li>
            <li><a href="{{url('/tasks/my')}}">Expired</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
          <div class="icon-text">
            <img src="{{url('frontend/images/customer.png')}}" alt=""> Clients
          </div>
          <div class="dropdown-arrow-div">
            <i class="fas fa-chevron-right dropdown-arrow"></i>
          </div>
          </a>
          <ul class="task-dropdown">
            <li><a href="{{url('/all-clients')}}">All Clients</a></li>
            <li><a href="{{url('/tasks/all')}}">Website</a></li>
            <li><a href="{{url('/tasks/my')}}">Microsoft</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
          <div class="icon-text">
            <img src="{{url('frontend/images/group.png')}}" alt=""> Prospects
          </div>
          <div class="dropdown-arrow-div">
            <i class="fas fa-chevron-right dropdown-arrow"></i>
          </div>
          </a>
          <ul class="task-dropdown">
            <li><a href="{{url('/prospects')}}">All </a></li>
          </ul>
        </li>
        @endif
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
          <div class="icon-text">
            <img src="{{url('frontend/images/blueprint.png')}}" alt=""> Projects
          </div>
          <div class="dropdown-arrow-div">
            <i class="fas fa-chevron-right dropdown-arrow"></i>
          </div>
          </a>
          <ul class="task-dropdown">
            <li><a href="{{url('/projects')}}">All</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
          <div class="icon-text">
            <img src="{{url('frontend/images/clipboard.png')}}" alt=""> Tasks
          </div>
          <div class="dropdown-arrow-div">
            <i class="fas fa-chevron-right dropdown-arrow"></i>
          </div>
          </a>
          <ul class="task-dropdown">
            <li><a href="{{ route('dashboard') }}">All Tasks</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.body.classList.toggle("sidebar-hidden");
    }

    function toggleDropdown(event) {
      event.stopPropagation();
      const dropdownMenu = document.getElementById("dropdownMenu");
      dropdownMenu.classList.toggle("show");
    }

    window.onclick = function(event) {
      const dropdownMenu = document.getElementById("dropdownMenu");
      if (dropdownMenu.classList.contains("show") && !event.target.closest('.profile-header')) {
        dropdownMenu.classList.remove("show");
      }
    };

    function toggleTaskDropdown(event) {
      event.preventDefault();
      const dropdown = event.target.closest('.dropdown');
      const taskDropdown = dropdown.querySelector('.task-dropdown');
      const dropdownArrow = dropdown.querySelector('.dropdown-arrow');

      taskDropdown.classList.toggle("show");
      dropdownArrow.classList.toggle("rotate");
    }

    function updateGreeting() {
      // Get the current time in Nepal using Intl.DateTimeFormat
      const options = {
        timeZone: "Asia/Kathmandu", // Specify the timezone
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
        hour12: false
      };

      const formatter = new Intl.DateTimeFormat([], options);
      const [hour, minute] = formatter.format(new Date()).split(':').map(Number); // Extract hours and minutes

      let greeting;

      // Log the current Nepal time for debugging
      console.log(`Current Nepal Time: ${hour}:${minute}`);

      // Determine the greeting based on the hour
      if (hour < 12) {
        greeting = "Good Morning, ";
      } else if (hour < 18) {
        greeting = "Good Afternoon, ";
      } else {
        greeting = "Good Evening, ";
      }

      // Display the greeting with username
      const username = "{{ $username }}"; // Using Laravel variable
      document.getElementById("greeting").innerText = greeting + username; // Combine greeting with username
    }

    // Call updateGreeting on page load
    window.onload = updateGreeting;
  </script>

</body>

</html>