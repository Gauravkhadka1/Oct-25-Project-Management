@extends('frontends.layouts.main')

@section('main-container')
   <main>
   <div class="thankyoupage">
       <div class="succ">
          <p>{{$msg}}</p>
       </div> <br>
       <div class="thnk">
          <p>{{$msg1}}</p>
       </div> 
       <div class="viewmr">
         <p>View more cause</p>
       </div>
       <a href="{{url('campaigns')}}">
          <button>View More</button>
       </a>
   </div>
   </main>
@endsection

