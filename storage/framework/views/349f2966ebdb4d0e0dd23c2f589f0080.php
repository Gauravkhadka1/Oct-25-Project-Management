<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Project Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.atwho/1.3.0/css/jquery.atwho.min.css" />
  <link rel="stylesheet" href="<?php echo e(asset('public/frontend/css/index.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/dashboard.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/projects.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/payments.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/prospects.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/add-client.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/new-dashboard.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/task-detail.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/client.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/client-detail.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/prospect-details-page.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/profile.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/payment-detail-page.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(url('public/frontend/css/expiry.css')); ?>">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">


  <?php
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



  ?>

</head>

<body>
  <div class="wrapper">
    <header>
      <nav>
        <ul class="horizontal-menu">
          <div class="start">
            <div class="h-menu">
              <img src="<?php echo e(url('public/frontend/images/hamburger.png')); ?>" class="menu-icon" onclick="toggleSidebar()">
            </div>
            <div class="greetings">
              <p id="greeting"></p>
            </div>
          </div>
          <div class="end">
            <div class="notification">
              <img src="<?php echo e(url('public/frontend/images/notification.png')); ?>" alt="Notifications" class="notification-icon" id="notification-icon">
              <span class="notification-count" id="notification-count">
                <?php echo e(auth()->user()->unreadNotifications->count()); ?>

              </span>

              <div class="notification-dropdown" id="notification-dropdown" style="display: none;">
                <ul>
                    <?php $__empty_1 = true; $__currentLoopData = auth()->user()->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li>
                            <!-- Check if 'message' exists, otherwise show a fallback value -->
                            <span><?php echo e($notification->data['message'] ?? 'No message available'); ?></span>
                            <small><?php echo e($notification->created_at->diffForHumans()); ?></small>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li>No new notifications</li>
                    <?php endif; ?>
                </ul>
                <button id="mark-all-read">Mark All as Read</button>
            </div>

            </div>

            <div class="profile-header" style="position: relative;">
              <a href="javascript:void(0);" onclick="toggleDropdown(event)">
              <div class="user-icon">
                  <?php if(auth()->user() && auth()->user()->profilepic): ?>
                      <img src="<?php echo e(asset('storage/profilepics/' . auth()->user()->profilepic)); ?>" alt="User Profile Picture" class="user-profile-pic">
                  <?php else: ?>
                      <img src="<?php echo e(asset('images/default-profile.png')); ?>" alt="Default Profile Picture" class="user-profile-pic">
                  <?php endif; ?>
              </div>




                <div class="name">
                  <p><?php echo e($username); ?></p>
                </div>
                <div class="dropdown-icon"><img src="<?php echo e(url('public/frontend/images/dropdown-arrow.png')); ?>" alt=""></div>
              </a>
              <div id="dropdownMenu" class="dropdown-menu">
                <a href="<?php echo e(route('dashboard')); ?>"><img src="<?php echo e(url('public/frontend/images/dashboard.png')); ?>" alt=""> Dashboard</a>
                <a href="<?php echo e(url('profile')); ?>"><img src="<?php echo e(url('public/frontend/images/profile.png')); ?>" alt=""> My Profile</a>
                <a href="<?php echo e(route('logout')); ?>"><img src="<?php echo e(url('public/frontend/images/logout.png')); ?>" alt="">Logout</a>
              </div>
            </div>
          </div>
        </ul>
      </nav>
    </header>

    <!-- Sidebar Menu -->
    <div id="sidebar" class="sidebar">
      <ul>
        <li><a href="<?php echo e(url('/dashboard')); ?>"> <img src="<?php echo e(url('public/frontend/images/wtn-logo-black.svg')); ?>" class="logo-img"></a></li>
         <li class="dropdown">
            <a href="<?php echo e(url('/projects')); ?>" class="task-toggle" >
              <div class="icon-text">
                  <img src="<?php echo e(url('public/frontend/images/blueprint.png')); ?>" alt=""> Projects
              </div>
              <div class="dropdown-arrow-div">
                <div class="number-count">
               <?php echo e($projectsCount); ?>

                </div>
                  <!-- <i class="fas fa-chevron-right dropdown-arrow"></i> -->
              </div>
            </a>
        </li>
        <?php if(auth()->check() && in_array(auth()->user()->email, $allowedEmails)): ?>
       
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
            <div class="icon-text">
              <img src="<?php echo e(url('public/frontend/images/renewable.png')); ?>" alt=""> Expiry
            </div>
            <div class="dropdown-arrow-div">
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>

          </a>
          <ul class="task-dropdown">
    <li><a href="<?php echo e(url('/expiry')); ?>">All</a></li>
    <li>
        <a href="<?php echo e(route('expiry.index', ['days_filter' => '35-31', 'sort' => request('sort'), 'column' => request('column')])); ?>">
            <div class="days">35 days</div>
            <div class="expiry-count"><?php echo e(getClientCount('35-31')); ?></div>
        </a>
    </li>
    <li>
        <a href="<?php echo e(route('expiry.index', ['days_filter' => '30-16', 'sort' => request('sort'), 'column' => request('column')])); ?>">
            <div class="days">30 days</div>
            <div class="expiry-count"><?php echo e(getClientCount('30-16')); ?></div>
        </a>
    </li>
    <li>
        <a href="<?php echo e(route('expiry.index', ['days_filter' => '15-8', 'sort' => request('sort'), 'column' => request('column')])); ?>">
            <div class="days">15 days</div>
            <div class="expiry-count"><?php echo e(getClientCount('15-8')); ?></div>
        </a>
    </li>
    <li>
        <a href="<?php echo e(route('expiry.index', ['days_filter' => '7-1', 'sort' => request('sort'), 'column' => request('column')])); ?>">
            <div class="days">7 days</div>
            <div class="expiry-count"><?php echo e(getClientCount('7-1')); ?></div>
        </a>
    </li>
    <li>
        <a href="<?php echo e(route('expiry.index', ['days_filter' => 'today', 'sort' => request('sort'), 'column' => request('column')])); ?>">
            <div class="days">Expiring Today</div>
            <div class="expiry-count"><?php echo e(getClientCount('today')); ?></div>
        </a>
    </li>
    <li>
        <a href="<?php echo e(route('expiry.index', ['days_filter' => 'expired', 'sort' => request('sort'), 'column' => request('column')])); ?>">
            <div class="days">Expired</div>
            <div class="expiry-count" id="expired"><?php echo e(getClientCount('expired')); ?></div>
        </a>
    </li>
</ul>

        </li>

        <li class="dropdown">
        <a href="<?php echo e(url('/prospects')); ?>" class="task-toggle" >
              <div class="icon-text">
              <img src="<?php echo e(url('public/frontend/images/group.png')); ?>" alt=""> Prospects
              </div>
              <div class="dropdown-arrow-div">
                <div class="number-count">
                  <?php echo e($prospectsCount); ?>

                </div>
                  <!-- <i class="fas fa-chevron-right dropdown-arrow"></i> -->
              </div>
            </a>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
            <div class="icon-text">
              <img src="<?php echo e(url('public/frontend/images/team.png')); ?>" alt=""> Teams
            </div>
            <div class="dropdown-arrow-div">
              <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
          </a>
          <ul class="task-dropdown">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="username-item" data-username="<?php echo e($user->username); ?>">
                <a href="<?php echo e(route('user.dashboard', ['username' => $user->username])); ?>">
                    <img src="<?php echo e(Storage::url('profilepics/' . $user->profilepic)); ?>" 
                        alt="<?php echo e($user->username); ?>'s Profile Picture" class="profile-pic">
                        <p>
                        <?php echo e($user->username); ?>

                        </p>
                </a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">
              <div class="icon-text">
                  <img src="<?php echo e(url('public/frontend/images/time.png')); ?>" alt=""> Payments
              </div>
              <div class="dropdown-arrow-div">
                  <i class="fas fa-chevron-right dropdown-arrow"></i>
              </div>
          </a>
            <ul class="task-dropdown">
                <!-- Redirect "All Payments" to the previous functionality -->
                <li><a href="<?php echo e(url('/payments')); ?>">Due Payments</a></li>

                <!-- Redirect "Today Paid" to Paid Payments -->
                <li><a href="<?php echo e(route('paid-payments.index', ['filter_date' => 'today'])); ?>">Today Paid</a></li>

                <!-- Redirect "This Week Paid" to Paid Payments -->
                <li><a href="<?php echo e(route('paid-payments.index', ['filter_date' => 'this_week'])); ?>">This Week Paid</a></li>

                <!-- Custom Days Input -->
                <li>
                    <form method="GET" action="<?php echo e(route('paid-payments.index')); ?>" class="days-filter-form">
                        <input type="number" name="days" placeholder="Enter days" required min="1" class="days-input" />
                        <button type="submit" class="days-submit">Show</button>
                    </form>
                </li>
            </ul>
        </li>

        <li class="dropdown">
        <a href="<?php echo e(url('/clients')); ?>" class="task-toggle" >
              <div class="icon-text">
              <img src="<?php echo e(url('public/frontend/images/customer.png')); ?>" alt=""> Clients
              </div>
              <div class="dropdown-arrow-div">
                  <i class="fas fa-chevron-right dropdown-arrow"></i>
              </div>
            </a>
        </li>
        

        

       
        <?php endif; ?>
        <!--<li class="dropdown">-->
        <!--  <a href="javascript:void(0);" class="task-toggle" onclick="toggleTaskDropdown(event)">-->
        <!--    <div class="icon-text">-->
        <!--      <img src="<?php echo e(url('public/frontend/images/clipboard.png')); ?>" alt=""> Tasks-->
        <!--    </div>-->
        <!--    <div class="dropdown-arrow-div">-->
        <!--      <i class="fas fa-chevron-right dropdown-arrow"></i>-->
        <!--    </div>-->
        <!--  </a>-->
        <!--  <ul class="task-dropdown">-->
        <!--    <li><a href="<?php echo e(route('dashboard')); ?>">My Tasks</a></li>-->
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
      const username = "<?php echo e($username); ?>"; // Using Laravel variable
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
        fetch('<?php echo e(route('notifications.markAllRead')); ?>', {
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

</html><?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/layouts/header.blade.php ENDPATH**/ ?>