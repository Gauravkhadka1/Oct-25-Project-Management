@extends('frontends.layouts.main')

@section('main-container')
<div class="loginpageb">

    <div class="loginh">
        <h1>Register</h1>
    </div>
   
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="rname">
                <x-input-label for="username" id="rnamer" :value="__('Username')" />
                <x-text-input id="name" class="rnameinp" type="text" name="username" :value="old('username')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="loginemail">
                <x-input-label for="email" id="lemailn" :value="__('Email')" />
                <x-text-input id="lemail" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="loginpswd">
                <x-input-label for="password" id="lpasswdn"  :value="__('Password')" />
                <x-text-input id="lpassword"  class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="loginpswd">
                <x-input-label for="password_confirmation" id="lpasswdn"  :value="__('Confirm Password')" />
                <x-text-input id="lpassword" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="forgtpslogin">
                <a class="frgtpswd" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="lregister">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    
</div>
@endsection
