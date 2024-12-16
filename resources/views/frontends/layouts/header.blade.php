<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Project Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.atwho/1.3.0/css/jquery.atwho.min.css" />
  <link rel="stylesheet" href="{{asset('public/frontend/css/index.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/dashboard.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/projects.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/payments.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/prospects.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/add-client.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/new-dashboard.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/task-detail.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/client.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/client-detail.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/prospect-details-page.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/profile.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/payment-detail-page.css')}}">
  <link rel="stylesheet" href="{{url('public/frontend/css/expiry.css')}}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">


  @php
  use App\Models\User;
  use App\Models\Project;
  use App\Models\Prospect;
  $users = User::select('id', 'username', 'profilepic')->get();
  $projectsCount = Project::where('status', '!=', 'completed')->count();
  $prospectsCount = Prospect::where('status', '!=', 'converted')->count();

  $allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
  $user = auth()->user();
  $username = $user->username;


  use App\Models\Clients;
use Carbon\Carbon;

// Retrieve the days filter from the request
$daysFilter = request('days_filter', '');

// Function to get client count based on filter
function getClientCount($daysFilter) {
    $clientsQuery = Clients::query();

    // Apply the days filter if specified
    switch ($daysFilter) {
        case '35-31':
            $clientsQuery->whereBetween('hosting_expiry_date', [Carbon::now()->addDays(31), Carbon::now()->addDays(35)]);
            break;
        case '30-16':
            $clientsQuery->whereBetween('hosting_expiry_date', [Carbon::now()->addDays(16), Carbon::now()->addDays(30)]);
            break;
        case '15-8':
            $clientsQuery->whereBetween('hosting_expiry_date', [Carbon::now()->addDays(8), Carbon::now()->addDays(15)]);
            break;
        case '7-1':
            $clientsQuery->whereBetween('hosting_expiry_date', [Carbon::now()->addDays(1), Carbon::now()->addDays(7)]);
            break;
        case 'today':
            $clientsQuery->whereDate('hosting_expiry_date', Carbon::today());
            break;
        case 'expired':
            $clientsQuery->where('hosting_expiry_date', '<', Carbon::today());
            break;
        default:
            break;
    }

    return $clientsQuery->count();
}



  @endphp

</head>

<body>
  <div class="wrapper">
    <header>
      <nav>
        <ul class="horizontal-menu">
          <div class="start">
            <div class="h-menu">
              <img src="{{url('public/frontend/images/hamburger.png')}}" class="menu-icon" onclick="toggleSidebar()">
            </div>
            <div class="greetings">
              <p id="greeting"></p>
            </div>
          </div>
          <div class="end">
            <div class="notification">
              <img src="{{ url('public/frontend/images/notification.png') }}" alt="Notifications" class="notification-icon" id="notification-icon">
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
                      <img src="{{ asset('storage/profilepics/' . auth()->user()->profilepic) }}" alt="User Profile Picture" class="user-profile-pic">
                  @else
                      <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="user-profile-pic">
                  @endif
              </div>




                <div class="name">
                  <p>{{ $username }}</p>
                </div>
                <div class="dropdown-icon"><img src="{{url('public/frontend/images/dropdown-arrow.png')}}" alt=""></div>
              </a>
              <div id="dropdownMenu" class="dropdown-menu">
                <a href="{{ route('dashboard') }}"><img src="{{url('public/frontend/images/dashboard.png')}}" alt=""> Dashboard</a>
                <a href="{{ url('profile') }}"><img src="{{url('public/frontend/images/profile.png')}}" alt=""> My Profile</a>
                <a href="{{ route('logout') }}"><img src="{{url('public/frontend/images/logout.png')}}" alt="">Logout</a>
              </div>
            </div>
          </div>
        </ul>
      </nav>
    </header>

    <!-- Sidebar Menu -->
    <div id="sidebar" class="sidebar">
      <ul>
        <li><a href="{{url('/dashboard')}}"> <img src="{{url('public/frontend/images/wtn-logo-black.svg')}}" class="logo-img"></a></li>
         <li class="dropdown">
            <a href="{{url('/projects')}}" class="task-toggle" >
              <div class="icon-text">
                  <img src="{{ url('public/frontend/images/blueprint.png') }}" alt=""> Projects
              </div>
              <div class="dropdown-arrow-div">
                <div class="number-count">
               {{ $projectsCount }}
                </div>
                  <!-- <i class="fas fa-chevron-right dropdown-arrow"></i> -->
              </div>
            </a>
        </li>
        @if(auth()->check() && in_array(auth()->user()->email, $allowedEmails))
       
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
            <div class="icon-text">
              <img src="{{url('public/frontend/images/renewable.png')}}" alt=""> Expiry
            </div>
            <div class="dropdown-arrow-div">
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>

          </a>
          <ul class="task-dropdown">
    <li><a href="{{ url('/expiry') }}">All</a></li>
    <li>
        <a href="{{ route('expiry.index', ['days_filter' => '35-31', 'sort' => request('sort'), 'column' => request('column')]) }}">
            <div class="days">35 days</div>
            <div class="expiry-count">{{ getClientCount('35-31') }}</div>
        </a>
    </li>
    <li>
        <a href="{{ route('expiry.index', ['days_filter' => '30-16', 'sort' => request('sort'), 'column' => request('column')]) }}">
            <div class="days">30 days</div>
            <div class="expiry-count">{{ getClientCount('30-16') }}</div>
        </a>
    </li>
    <li>
        <a href="{{ route('expiry.index', ['days_filter' => '15-8', 'sort' => request('sort'), 'column' => request('column')]) }}">
            <div class="days">15 days</div>
            <div class="expiry-count">{{ getClientCount('15-8') }}</div>
        </a>
    </li>
    <li>
        <a href="{{ route('expiry.index', ['days_filter' => '7-1', 'sort' => request('sort'), 'column' => request('column')]) }}">
            <div class="days">7 days</div>
            <div class="expiry-count">{{ getClientCount('7-1') }}</div>
        </a>
    </li>
    <li>
        <a href="{{ route('expiry.index', ['days_filter' => 'today', 'sort' => request('sort'), 'column' => request('column')]) }}">
            <div class="days">Expiring Today</div>
            <div class="expiry-count">{{ getClientCount('today') }}</div>
        </a>
    </li>
    <li>
        <a href="{{ route('expiry.index', ['days_filter' => 'expired', 'sort' => request('sort'), 'column' => request('column')]) }}">
            <div class="days">Expired</div>
            <div class="expiry-count" id="expired">{{ getClientCount('expired') }}</div>
        </a>
    </li>
</ul>

        </li>

        <li class="dropdown">
        <a href="{{url('/prospects')}}" class="task-toggle" >
              <div class="icon-text">
              <img src="{{url('public/frontend/images/group.png')}}" alt=""> Prospects
              </div>
              <div class="dropdown-arrow-div">
                <div class="number-count">
                  {{$prospectsCount }}
                </div>
                  <!-- <i class="fas fa-chevron-right dropdown-arrow"></i> -->
              </div>
            </a>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
            <div class="icon-text">
              <img src="{{url('public/frontend/images/team.png')}}" alt=""> Teams
            </div>
            <div class="dropdown-arrow-div">
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
          </a>
          <ul class="task-dropdown">
            @foreach ($users as $user)
            <li class="username-item" data-username="{{ $user->username }}">
                <a href="{{ route('user.dashboard', ['username' => $user->username]) }}">
                    <img src="{{ Storage::url('profilepics/' . $user->profilepic) }}" 
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
                <li><a href="{{ url('/payments') }}">Due Payments</a></li>

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

        <li class="dropdown">
        <a href="{{url('/clients')}}" class="task-toggle" >
              <div class="icon-text">
              <img src="{{url('public/frontend/images/customer.png')}}" alt=""> Clients
              </div>
              <div class="dropdown-arrow-div">
                  <i class="fas fa-chevron-right dropdown-arrow"></i>
              </div>
            </a>
        </li>
        

        

       
        @endif
        <!--<li class="dropdown">-->
        <!--  <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">-->
        <!--    <div class="icon-text">-->
        <!--      <img src="{{url('public/frontend/images/clipboard.png')}}" alt=""> Tasks-->
        <!--    </div>-->
        <!--    <div class="dropdown-arrow-div">-->
        <!--      <i class="fas fa-chevron-right dropdown-arrow"></i>-->
        <!--    </div>-->
        <!--  </a>-->
        <!--  <ul class="task-dropdown">-->
        <!--    <li><a href="{{ route('dashboard') }}">My Tasks</a></li>-->
        <!--  </ul>-->
        <!--</li>-->

        
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