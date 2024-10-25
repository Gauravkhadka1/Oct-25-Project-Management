@extends('frontends.layouts.main')

@section('main-container')
    <main>
        <div class="hero">
          <div class="hero-text">
            <div class="herotext1">
                <h1>
                    Let's Fund
                </h1>
            </div>
            <div class="herotext2">
                <h1>
                    Nepal
                </h1>
            </div>
          </div>
          <div class="herobuttons">
            <button class="learn_more">
                Raise Fund
            </button>
            <button class="donate_now">
               Give Fund
            </button>
          </div>
        </div>
           
    <!--hero ends-->
    
    <!-- aboutus starts -->
    <div class="aboutlfn">
    
        <div class="aboutlfnpic">
            <img src="frontend/images/about us.jpg" alt="">
        </div>
        <div class="aboutlfntext">
            <h2>About us</h2>
            <h3>Let's Fund Nepal</h3>
            <p>Let's Fund Nepal is officially registered NGO (Non Governmental Organization) in Nepal which helps people to raise fund and give fund.</p>
            <h3> Our Mission</h3>
            <p>To help the needy people get fund they needed.</p>
            <h3>How it works?</h3>
            <p>You can raise fund by submiting your need and goals. Our team will verify and approve it. After approval your post will be posted and you can get fund directly in your bank account.</p>
            <button>Learn More</button>
        </div>
    
    </div>
    
    <!--aboutus ends-->
    
    <div class="current-works">
        <div class="containeri swiper">  
            <div class="current-worksi-heading">
                <h2>Campaigns</h2>
            </div> 
            <div class="slider-wrapper">
                <div class="campaigns swiper-wrapper">
                    <div class="campaign swiper-slide">
                        <a href="campaign1">
                            <img src="{{url('frontend/images/comingsoond.png')}}" alt="" class="campaign-image">
                        </a>
                        <div class="catntime">
                            <div class="cat">
                                 Medical
                            </div>
                            <div class="time">
                                <i class="fa-regular fa-clock"></i> 5 days left
                            </div>
                        </div>
                            <div class="campaignheadingi">
                                <h3>Campaign Raising Soon</h3>
                            </div>
                            <div class="amtnperc">
                                <div class="raisedamount">
                                    Raised: {{ $totalRaised1 }}
                                </div>
                                <div class="raisedpercentage">
                                    {{ $percentageRaised1 }} %
                                </div>    
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill" style="width: 50%;"></div>
                            </div>
                           <div class="goalnoofdonations">
                                    <div class="goal">
                                        Target:  <div class="tamt"> {{ $targetAmount1 }}</div>
                                    </div>
                                    <div class="noofdonations">
                                        <p>{{ $totalDonations }} Donations</p>
                                    </div>
                            </div>
                        
                    </div>
                    <div class="campaign swiper-slide">
                        <a href="campaign1">
                            <img src="{{url('frontend/images/comingsoond.png')}}" alt="" class="campaign-image">
                        </a>
                        <div class="catntime">
                            <div class="cat">
                                 Medical
                            </div>
                            <div class="time">
                                <i class="fa-regular fa-clock"></i> 5 days left
                            </div>
                        </div>
                            <div class="campaignheadingi">
                                <h3>Campaign Raising Soon</h3>
                            </div>
                            <div class="amtnperc">
                                <div class="raisedamount">
                                    Raised: {{ $totalRaised1 }}
                                </div>
                                <div class="raisedpercentage">
                                    {{ $percentageRaised1 }} %
                                </div>    
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill" style="width: 50%;"></div>
                            </div>
                             <div class="goalnoofdonations">
                                    <div class="goal">
                                        Target:  <div class="tamt"> {{ $targetAmount1 }}</div>
                                    </div>
                                    <div class="noofdonations">
                                        <p>{{ $totalDonations }} Donations</p>
                                    </div>
                            </div>
                        
                    </div>
                    <div class="campaign swiper-slide">
                        <a href="campaign1">
                            <img src="{{url('frontend/images/comingsoond.png')}}" alt="" class="campaign-image">
                        </a>
                        <div class="catntime">
                            <div class="cat">
                                 Medical
                            </div>
                            <div class="time">
                                <i class="fa-regular fa-clock"></i> 5 days left
                            </div>
                        </div>
                            <div class="campaignheadingi">
                                <h3>Campaign Raising Soon</h3>
                            </div>
                            <div class="amtnperc">
                                <div class="raisedamount">
                                    Raised: {{ $totalRaised1 }}
                                </div>
                                <div class="raisedpercentage">
                                    {{ $percentageRaised1 }} %
                                </div>    
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill" style="width: 50%;"></div>
                            </div>
                             <div class="goalnoofdonations">
                                    <div class="goal">
                                        Target:  <div class="tamt"> {{ $targetAmount1 }}</div>
                                    </div>
                                    <div class="noofdonations">
                                        <p>{{ $totalDonations }} Donations</p>
                                    </div>
                            </div>
                        
                    </div>
                    <div class="campaign swiper-slide">
                        <a href="campaign1">
                            <img src="{{url('frontend/images/comingsoond.png')}}" alt="" class="campaign-image">
                        </a>
                        <div class="catntime">
                            <div class="cat">
                                 Medical
                            </div>
                            <div class="time">
                                <i class="fa-regular fa-clock"></i> 5 days left
                            </div>
                        </div>
                            <div class="campaignheadingi">
                                <h3>Campaign Raising Soon</h3>
                            </div>
                            <div class="amtnperc">
                                <div class="raisedamount">
                                    Raised: {{ $totalRaised1 }}
                                </div>
                                <div class="raisedpercentage">
                                    {{ $percentageRaised1 }} %
                                </div>    
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill" style="width: 50%;"></div>
                            </div>
                             <div class="goalnoofdonations">
                                    <div class="goal">
                                        Target:  <div class="tamt"> {{ $targetAmount1 }}</div>
                                    </div>
                                    <div class="noofdonations">
                                        <p>{{ $totalDonations }} Donations</p>
                                    </div>
                            </div>
                        
                    </div>
                    <div class="campaign swiper-slide">
                        <a href="campaign1">
                            <img src="{{url('frontend/images/comingsoond.png')}}" alt="" class="campaign-image">
                        </a>
                        <div class="catntime">
                            <div class="cat">
                                 Medical
                            </div>
                            <div class="time">
                                <i class="fa-regular fa-clock"></i> 5 days left
                            </div>
                        </div>
                            <div class="campaignheadingi">
                                <h3>Campaign Raising Soon</h3>
                            </div>
                            <div class="amtnperc">
                                <div class="raisedamount">
                                    Raised: {{ $totalRaised1 }}
                                </div>
                                <div class="raisedpercentage">
                                    {{ $percentageRaised1 }} %
                                </div>    
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill" style="width: 50%;"></div>
                            </div>
                             <div class="goalnoofdonations">
                                    <div class="goal">
                                        Target:  <div class="tamt"> {{ $targetAmount1 }}</div>
                                    </div>
                                    <div class="noofdonations">
                                        <p>{{ $totalDonations }} Donations</p>
                                    </div>
                            </div>
                        
                    </div>
                    <div class="campaign swiper-slide">
                        <a href="campaign1">
                            <img src="{{url('frontend/images/comingsoond.png')}}" alt="" class="campaign-image">
                        </a>
                        <div class="catntime">
                            <div class="cat">
                                 Medical
                            </div>
                            <div class="time">
                                <i class="fa-regular fa-clock"></i> 5 days left
                            </div>
                        </div>
                            <div class="campaignheadingi">
                                <h3>Campaign Raising Soon</h3>
                            </div>
                            <div class="amtnperc">
                                <div class="raisedamount">
                                    Raised: {{ $totalRaised1 }}
                                </div>
                                <div class="raisedpercentage">
                                    {{ $percentageRaised1 }} %
                                </div>    
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar-fill" style="width: 50%;"></div>
                            </div>
                              <div class="goalnoofdonations">
                                    <div class="goal">
                                        Target:  <div class="tamt"> {{ $targetAmount1 }}</div>
                                    </div>
                                    <div class="noofdonations">
                                        <p>{{ $totalDonations }} Donations</p>
                                    </div>
                            </div>
                        
                    </div>
                  
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-slide-button swiper-button-prev"></div>
                <div class="swiper-slide-button swiper-button-next"></div>
            </div>
        </div>
        <a href="{{url('/campaigns')}}">
            <div class="current-worksi-button">
               <p>See more</p>
            </div>
        </a>
    </div>
        
    
    
    
    
        <div class="donators-tablei">
           <div class="donators-tablei-heading">
             <h2>Contributors</h2>
           </div>
        
           <div class="donators-list">
                <div class="mob-heading">
                    <div class="mob-heading-recent" onclick="toggleTable('recent', this)">
                        <h2>Recent</h2>
                    </div>
                    <div class="mob-heading-top" onclick="toggleTable('top', this)">
                        <h2>Top</h2>
                    </div>

                </div>
                <div class="recent-donatorsi" id="recent-table">
                    <div class="recent-donators-headingi">
                        <h2>Recent </h2>
                    </div>
                    <table class="content-tablei">
                        <thead>
                            <tr>
                                <th class="sn-col">SN</th>
                                <th class="name-col" id="dtnam">Name</th>
                                <th class="amount-col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            // Fetch recent donators ordered by donation time from both campaign1 and campaign2 tables
                            $recentDonators = DB::select('
                            (SELECT email, name, amount, profilepic, created_at FROM campaign1 
                            UNION ALL
                            SELECT email, name, amount, profilepic, created_at FROM campaign2) 
                            ORDER BY created_at DESC 
                            LIMIT 10
                            ');
                            $sn = 1;
                            @endphp
                            @foreach ($recentDonators as $donator)
                            <tr>
                                <td class="sn-col">{{ $sn++ }}</td>
                                <td class="name-col"><a href="{{ route('profile.show', $donator->email) }}">   <div class="userpicdt"> 
                                    @if($donator->profilepic)
                                        <img src="{{ asset('storage/' . $donator->profilepic) }}" alt="Profile Picture" class="userpicdt">
                                    @else
                                    <img src="{{url('frontend/images/defaultuserpic.png')}}" class="userpicdt">
                                    @endif
                                </div>{{ $donator->name }}</a></td> <!-- Assuming 'email' is the user's unique identifier -->
                                <td class="amount-col">{{ $donator->amount }}</td> <!-- Assuming 'amount' is the donation amount -->
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="dtrseemore"> <a href="{{url('/contributors')}}">See more</a></div>
                </div>

                <div class="top-donatorsi" id="top-table">
                    <div class="top-donators-headingi">
                        <h2>Top </h2>
                    </div>
                    <table class="content-tablei">
                        <thead>
                            <tr>
                                <th class="sn-col">SN</th>
                                <th class="name-col">Name</th>
                                <th class="amount-col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                        // Fetch top donators ordered by total amount donated from both campaign1 and campaign2 tables
                        $topDonators = DB::select('
                        SELECT email, name, profilepic, SUM(total_amount) as total_amount FROM (
                            SELECT email, name, profilepic, SUM(amount) as total_amount FROM campaign1 GROUP BY email, name, profilepic
                            UNION ALL
                            SELECT email, name, profilepic, SUM(amount) as total_amount FROM campaign2 GROUP BY email, name, profilepic
                        ) AS combined_donations
                        GROUP BY email, name, profilepic
                        ORDER BY total_amount DESC
                        LIMIT 10
                        ');
                        $sn = 1;

                        @endphp
                        @foreach ($topDonators as $donator)
                        <tr>
                            <td class="sn-col">{{ $sn++ }}</td>
                            <td class="name-col"><a href="{{ route('profile.show', $donator->email) }}"><div class="userpicdt"> 
                                    @if($donator->profilepic)
                                        <img src="{{ asset('storage/' . $donator->profilepic) }}" alt="Profile Picture" class="userpicdt">
                                    @else
                                    <img src="{{url('frontend/images/defaultuserpic.png')}}" class="userpicdt">
                                    @endif
                                </div>{{ $donator->name }}</a></td>
                            <td class="amount-col">{{ $donator->total_amount }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="dtrseemore"><a href="{{url('/contributors')}}">See more</a></div>
                </div>
            </div>

        </div>
        <!-- <section class="blogs">
            <div class="blogh">
                <h1>Blogs</h1>
                <h1>Blogs</h1>
            </div>    
      
            <div class="blog-container">
                <div class="blog-box">
                    <div class="blog-img1">
                        <img src="{{url('frontend/images/who we are.jpg')}}" alt="blog-img1">
                    </div>
                    <div class="blog-text1">
                        <span class="blog-time">
                            5 May, 2024
                        </span>
                        <div class="blog1-heading">
                        <h3>what is come on Nepal ?</h3>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed itaque porro blanditiis, consequatur corrupti nostrum consectetur nemo eos rem non Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, atque..</p>
                        <a href="#">Read More</a>
                    </div>
                        
                </div>
                <div class="blog-box">
                    <div class="blog-img1">
                        <img src="{{url('frontend/images/how we work.jpg')}}" alt="blog-img1">
                    </div>
                    <div class="blog-text1">
                        <span class="blog-time">
                            5 May, 2024
                        </span>
                        <div class="blog2-heading">
                        <h3>How come on Nepal Works ?</h3>
                        </div> 
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed itaque porro blanditiis, consequatur corrupti nostrum consectetur nemo eos rem non Lorem, ipsum dolor sit amet consectetur adipisicing elit. Explicabo, quos! .</p>
                        <a href="#">Read More</a>
                    </div>
                </div>
               
            </div>
                    
        </section> -->
       
       
       
       
         <div class="faqbox">
            <div class="faqcontainer">
                <div class="faqheading">
                    <h2>FAQ's</h2>
                </div>
                <div class="wholewrapper">
                    <div class="wrapper">
                        <button class="toggle">
                            What is Come on Nepal ?
                            <i class="fas fa-plus faqicon"></i>
                        </button>
                        <div class="faqcontent">
                            <p>Come on Nepal is a crowdfunding platform which runs the campaigns for the needy people.</p>
                        </div>
                    </div>
                    <div class="wrapper">
                        <button class="toggle">
                        Is it trustworthy ?
                            <i class="fas fa-plus faqicon"></i>
                        </button>
                        <div class="faqcontent">
                            <p>Yes, because Come on Nepal verifies and raise the campaigns for needy people only. The amount is also directly deposited in the respective person account.</p>
                        </div>
                    </div>
                    <div class="wrapper">
                        <button class="toggle">
                        How it works ?
                            <i class="fas fa-plus faqicon"></i>
                        </button>
                        <div class="faqcontent">
                            <p>Come on Nepal creates a campaign and boost it on social media platform to help people get fund from large no of people. The raised amount is directly deposited in the related person account.</p>
                        </div>
                    </div>
                    <div class="wrapper">
                        <button class="toggle">
                        Is the donation secure ?
                            <i class="fas fa-plus faqicon"></i>
                        </button>
                        <div class="faqcontent">
                            <p>Yes, the donation is secured. </p>
                        </div>
                    </div>
                    <div class="wrapper">
                        <button class="toggle">
                        What is the benefit for Come on Nepal?
                            <i class="fas fa-plus faqicon"></i>
                        </button>
                        <div class="faqcontent">
                            <p>Some kind hearted people leave certain amount of tip for Come on Nepal.</p>
                        </div>
                    </div>
                    <div class="wrapper">
                        <button class="toggle">
                        How can I request to raise fund for my campaigns ?
                            <i class="fas fa-plus faqicon"></i>
                        </button>
                        <div class="faqcontent">
                            <p>You can contact Come on Nepal via email, phone, whats app or any other your prefered method. They will respond to you as earliest possible.</p>
                        </div>
                    </div>
                </div>
            </div>
       </div>


        <!-- <section class="partners-section">
            <h2>Our Partners</h2>
                <div class="logo-slider">
                    <div class="logo-container">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 1">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 2">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 3">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 1">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 2">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 3">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 1">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 2">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 3">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 1">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 2">
                        <img src="{{url('frontend/images/logos.png')}}" alt="Partner 3">
                       
                    </div>
                </div>
        </section> -->





        <section class="contact-us">
                <div class="contactusi-heading">
                    <h2>Contact us</h2>
                </div>
            
            <div class="contact-method">
                
                <div class="contact-form">
                    <h3>Send us a message</h3>

                    @if (Session::has('msg'))
                    <p class="alert alert-success">{{Session::get('msg')}}</p>
                    @endif

                    <form action="/postmessage" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="access_key" value="5cafe6f6-176f-4500-8d69-05ac8763b896">
                            <input type="text" name="name" placeholder="Your Name">
                        </div>
                        <div class="form-group">
                            <input type="text" name="email" placeholder="Your email address">
                        </div>
                        <div class="form-group">
                            <input type="number" name="contact" placeholder="Your Contact number">
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your messsage here"></textarea>
                        </div>
                        <button type="submit">Send Message</button>
                      
                    </form>
                </div>
                <div class="contact-info">
                    <h3>Contact information</h3>
                    <div class="contact-p">
                        <div class="email">
                           <i class="fa-solid fa-envelope"></i>
                           <div class="emailtxt">
                                <p1>Email:</p1>
                                <p>info@comeonnepal.com</p>
                           </div>
                        </div>
                        <div class="phone">
                           <i class="fa-solid fa-phone"></i>
                           <div class="phonetxt">
                            <p1>Phone:</p1>
                            <p>9817398156</p>
                           </div>
                        </div>
                        <div class="addressi">
                           <i class="fa-solid fa-location-dot"></i>
                           <div class="addresstxti">
                                <p1>Address:</p1> <br>
                                <p>Kathmandu, Nepal</p>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
   @endsection