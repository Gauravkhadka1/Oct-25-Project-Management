@extends('frontends.layouts.main')

@section('main-container')
<div class="resetpsw">
    <div class="resetpswform">
        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="rpemail">
                <x-input-label for="email" id="rpemailt" :value="__('Email')" />
                <x-text-input class="rpemailf" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="rpp">
                <x-input-label for="password" id="rppt" :value="__('Password')" />
                <x-text-input class="rppf" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="rpcp">
                <x-input-label for="password_confirmation" id="rpcpt" :value="__('Confirm Password')" />

                <x-text-input class="rpcpf"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="rpbtn">
                <button>
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
    @endsection
