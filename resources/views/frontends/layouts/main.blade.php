@php
    if (!auth()->check()) {
        return redirect()->route('login');
    }
@endphp

<div id="main-container" class="main-container">
      @include('frontends.layouts.header')
      @yield('main-container')
      @include('frontends.layouts.footer')
    </div>