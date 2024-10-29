<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Project Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.atwho/1.3.0/css/jquery.atwho.min.css" />

    <link rel="stylesheet" href="<?php echo e(url('frontend/css/index.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/login.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/signup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/projects.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/forgotpassword.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/resetpassword.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/user-dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('frontend/css/prospects.css')); ?>">
    <link
  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 0 0" width="0" height="0" style="display:none;">
        <defs>
            <filter id="goo">
                <feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur" /> 
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
            </filter>
        </defs>
    </svg>

    <?php
    $allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
    $user = auth()->user();
         $userEmail = $user->email;
         $username = $user->username;
?>

     

</head>
<body>

    <div id="progress-top">
        <span id="progress-top-value">&#x1F815;</span>
    </div>

    <header>
        <nav>

                <ul class="sidebar">
                    <li onclick=hideSidebar()><a href="<?php echo e(url('/')); ?>"><svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#5f6368"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
                    <?php if(auth()->guard()->check()): ?>
                        <li><a href="<?php echo e(route('dashboard')); ?>">
                        <?php if(auth()->user()->profilepic): ?>
                        <img src="<?php echo e(asset('storage/' . auth()->user()->profilepic)); ?>" alt="Profile Picture" class="userpic">
                        <?php else: ?>
                        <img src="<?php echo e(url('frontend/images/defaultuserpic.png')); ?>" class="userpic">
                        <?php endif; ?>

                 
                      <?php endif; ?>
                      <!-- <li class="hideOnMobile"><a href="<?php echo e(url('/contributors')); ?>" class="<?php echo e(Request::is('contributors') ? 'active' : ''); ?>">Payments</a></li> -->
                      <!-- <li class="hideOnMobile"><a href="<?php echo e(url('/about')); ?>" class="<?php echo e(Request::is('about') ? 'active' : ''); ?>">Prospects</a></li> -->
                    <li class="hideOnMobile"><a href="<?php echo e(url('/campaigns')); ?>" class="<?php echo e(Request::is('campaigns') ? 'active' : ''); ?>">Projects</a></li>
                    <!-- <li class="hideOnMobile"><a href="<?php echo e(url('/contributors')); ?>" class="<?php echo e(Request::is('contributors') ? 'active' : ''); ?>">Renewals</a></li> -->
                      
                </ul>
                
            
                <ul>
                    <li><a href="<?php echo e(url('/')); ?>"> <img src="<?php echo e(url('frontend/images/wtn.png')); ?>" class="logo-img"></a></li>

                    <!-- <form action="<?php echo e(route('search')); ?>" method="GET" class="searchbar">
                        <div class="filtericon">
                          <img src="frontend/images/filter.png" alt="">
                        </div>
                        <input type="text" name="query" placeholder="Search..." class="searchform">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form> -->

                    <?php if(auth()->check() && in_array(auth()->user()->email, $allowedEmails)): ?>
                      <!-- <li class="hideOnMobile"><a href="<?php echo e(url('/payments')); ?>" class="<?php echo e(Request::is('contributors') ? 'active' : ''); ?>">Payments</a></li> -->
                      <li class="hideOnMobile"><a href="<?php echo e(url('/prospects')); ?>" class="<?php echo e(Request::is('about') ? 'active' : ''); ?>">Prospects</a></li>
                      <!-- <li class="hideOnMobile"><a href="<?php echo e(url('/contributors')); ?>" class="<?php echo e(Request::is('contributors') ? 'active' : ''); ?>">Renewals</a></li> -->
                  <?php endif; ?>


                  
                    <li class="hideOnMobile"><a href="<?php echo e(url('/projects')); ?>" class="<?php echo e(Request::is('campaigns') ? 'active' : ''); ?>">Projects</a></li>
                   
                  
                      <?php if(auth()->guard()->check()): ?>
                      <div class="profile-header">
                        <a href="<?php echo e(url('/dashboard')); ?>">
                        <div class="user-icon">
                          <img src="<?php echo e(url('frontend/images/user.png')); ?>" alt="">
                        </div>
                          <div class="name"><?php echo e($username); ?></div>
                        </a>
                       </div>
                        

                        
                      <?php endif; ?>
                  
                      <!-- <li class="menu-button" onclick=showSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li> -->
                      <li class="menu-button" onclick=showSidebar()><a href="#"><i class="fa-solid fa-bars"></i></a></li>
                </ul>
         </nav>
        </header>
       <?php /**PATH C:\xampp\htdocs\Oct 29- Live edited-project management\resources\views/frontends/layouts/header.blade.php ENDPATH**/ ?>