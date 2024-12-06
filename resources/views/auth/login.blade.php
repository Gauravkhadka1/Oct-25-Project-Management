<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{url('public/frontend/css/login.css')}}">
</head>
<body>
    <div class="login-page">
        <div class="loginpageb">
            <div class="loginpageinput">
                <div class="wtnlogo">
                    <img src="public/frontend/images/wtn.png" alt="">
                </div>
                <div class="loginh">
                    <h1>Login</h1>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="loginemail">
                        <x-input-label for="email" id="lemailn" :value="__('Email')" />
                        <x-text-input id="lemail" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="loginpswd">
                        <x-input-label for="password" id="lpasswdn" :value="__('Password')" />
                        <x-text-input id="lpassword" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="rememberl">
                        <label for="remember_me" class="reml">
                            <input id="remlc" type="checkbox" name="remember">
                            <span class="remtext">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="forgtpslogin">
                        @if (Route::has('password.request'))
                            <!-- <a class="frgtpswd" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a> -->
                        @endif

                        <x-primary-button class="llogin">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>