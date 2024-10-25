@extends('frontends.layouts.main')

@section('main-container')
    <div class="frgtpswdpg">
        <div class="forgtform">
            <div class="forgtmsg">
               <p>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="frgtem">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input class="frem" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="frgtbtn">
                    <button>
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endsection
