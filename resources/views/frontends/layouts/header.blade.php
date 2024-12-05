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
  <link rel="stylesheet" href="{{url('frontend/css/projects.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/payments.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/prospects.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/add-client.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/new-dashboard.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/task-detail.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/client.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/prospect-details-page.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/profile.css')}}">
  <link rel="stylesheet" href="{{url('frontend/css/payment-detail-page.css')}}">

  @php
  use App\Models\User;
  $users = User::select('id', 'username', 'profilepic')->get();

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
              <img src="{{ url('frontend/images/notification.png') }}" alt="Notifications" class="notification-icon" id="notification-icon">
              <span class="notification-count" id="notification-count">
                {{ auth()->user()->unreadNotifications->count() }}
              </span>

              <div class="notification-dropdown" id="notification-dropdown" style="display: none;">
    <ul>
        @forelse(auth()->user()->unreadNotifications as $notification)
            <li>
                <!-- Check if 'message' exists, otherwise show a fallback value -->
                <span>{{ $notification->data['message'] ?? 'No message available' }}</span>
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </li>
        @empty
            <li>No new notifications</li>
        @endforelse
    </ul>
    <button id="mark-all-read">Mark All as Read</button>
</div>

            </div>

            <div class="profile-header" style="position: relative;">
              <a href="javascript:void(0);" onclick="toggleDropdown(event)">
              <div class="user-icon">
                  @if(auth()->user() && auth()->user()->profilepic)
                      <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profilepic) }}" alt="User Profile Picture" class="user-profile-pic">
                  @else
                      <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="user-profile-pic">
                  @endif
              </div>




                <div class="name">
                  <p>{{ $username }}</p>
                </div>
                <div class="dropdown-icon"><img src="{{url('frontend/images/dropdown-arrow.png')}}" alt=""></div>
              </a>
              <div id="dropdownMenu" class="dropdown-menu">
                <a href="{{ route('dashboard') }}"><img src="{{url('frontend/images/dashboard.png')}}" alt=""> Dashboard</a>
                <a href="{{ url('profile') }}"><img src="{{url('frontend/images/profile.png')}}" alt=""> My Profile</a>
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
            <img src="{{ url('public/frontend/images/time.png') }}" alt=""> Payments
        </div>
        <div class="dropdown-arrow-div">
            <i class="fas fa-chevron-right dropdown-arrow"></i>
        </div>
    </a>
    <ul class="task-dropdown">
        <!-- Redirect "All Payments" to the previous functionality -->
        <li><a href="{{ url('/payments') }}">All Payments</a></li>

        <!-- Redirect "Today Paid" to Paid Payments -->
        <li><a href="{{ route('paid-payments.index', ['filter_date' => 'today']) }}">Today Paid</a></li>

        <!-- Redirect "This Week Paid" to Paid Payments -->
        <li><a href="{{ route('paid-payments.index', ['filter_date' => 'this_week']) }}">This Week Paid</a></li>

        <!-- Custom Days Input -->
        <li>
            <form method="GET" action="{{ route('paid-payments.index') }}" class="days-filter-form">
                <input type="number" name="days" placeholder="Enter days" required min="1" class="days-input" />
                <button type="submit" class="days-submit">Show</button>
            </form>
        </li>
    </ul>
</li>

        <!-- <li class="dropdown">
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
        </li> -->
        <li class="dropdown" id="nodropdown-project">
            <a href="{{url('/projects')}}">
              <div class="nodropdown" >
                <img src="{{url('frontend/images/blueprint.png')}}" alt=""> Projects
              </div>
            </a>
        </li>
        <li class="dropdown">
          <a href="{{url('/prospects')}}">
            <div class="nodropdown">
              <img src="{{url('frontend/images/group.png')}}" alt=""> Prospects
            </div>
          </a>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
            <div class="icon-text">
              <img src="{{url('frontend/images/team.png')}}" alt=""> Teams
            </div>
            <div class="dropdown-arrow-div">
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
          </a>
          <ul class="task-dropdown">
            @foreach ($users as $user)
            <li class="username-item" data-username="{{ $user->username }}">
                <a href="{{ route('user.dashboard', ['username' => $user->username]) }}">
                    <img src="{{ Storage::url('profile_pictures/' . $user->profilepic) }}" 
                        alt="{{ $user->username }}'s Profile Picture" class="profile-pic">
                        <p>
                        {{ $user->username }}
                        </p>
                    
                </a>
            </li>
            @endforeach
        </ul>

        </li>

        <li class="dropdown">
          <a href="/clients">
            <div class="nodropdown">
              <img src="{{url('frontend/images/customer.png')}}" alt=""> Clients
            </div>
          </a>
        </li>
        

        

       
        @endif
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
            <li><a href="{{ route('dashboard') }}">My Tasks</a></li>
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

    document.addEventListener('DOMContentLoaded', function() {
    const notificationIcon = document.getElementById('notification-icon');
    const notificationCount = document.getElementById('notification-count');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const markAllReadButton = document.getElementById('mark-all-read');

    // Toggle notification dropdown when clicking on the notification icon or count
    notificationIcon.addEventListener('click', function() {
        notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';
    });

    notificationCount.addEventListener('click', function() {
        notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';
    });

    // Close the notification dropdown if clicked outside of it
    document.addEventListener('click', function(event) {
        if (!notificationDropdown.contains(event.target) && event.target !== notificationIcon && event.target !== notificationCount) {
            notificationDropdown.style.display = 'none';
        }
    });

    // Handle "Mark All as Read" button click
    markAllReadButton.addEventListener('click', function() {
        // Send AJAX request to mark all notifications as read
        fetch('{{ route('notifications.markAllRead') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({}) // You can pass additional data if needed
        })
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            if (data.success) {
                // If the request was successful, update the notification count and UI
                document.getElementById('notification-count').textContent = '0'; // Set count to 0 or however you want
                // Optionally, hide the notification dropdown or do other UI updates
                document.getElementById('notification-dropdown').style.display = 'none';
            } else {
                console.error('Error marking notifications as read:', data.error);
            }
        })
        .catch(error => {
            console.error('Error with the request:', error);
        });
    });
});



  </script>

</body>

</html>