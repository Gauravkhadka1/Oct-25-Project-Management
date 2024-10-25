@extends('frontends.layouts.main')

@section('main-container')

    <div class="verifyemail">
        <div class="verifyemailcontent">
            <div class="verifyemailtext">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="vrl">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div class="vresend"> 
                        <button>
                            {{ __('Resend Verification Email') }}
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <div class="vlogout">
                        <button type="submit" class="verify-logout">
                            {{ __('Log Out') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection