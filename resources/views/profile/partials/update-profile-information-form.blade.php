@extends('frontends.layouts.main')

@section('main-container')
@php
use App\Models\User;
$users = User::select('id', 'username')->get();

$allowedEmails = ['gaurav@webtech.com.np', 'suraj@webtechnepal.com', 'sudeep@webtechnepal.com', 'sabita@webtechnepal.com'];
$user = auth()->user();
$username = $user->username;
@endphp
<main>
    <div class="updtprof">
        <section class="profieinf">
            <!-- <h2>
                {{ __('Profile Information') }}
            </h2> -->
            <div class="user-image">
                @if(auth()->user() && auth()->user()->profilepic)
                <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profilepic) }}" alt="User Profile Picture" class="user-profile-pic">
                @else
                <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="user-profile-pic">
                @endif
            </div>
            <div class="user-name">
                <p>{{ $username }}</p>
            </div>

            <p>
                {{ __("Update your account.") }}
            </p>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.updateProfile') }}" enctype="multipart/form-data" class="updtprfform">
                @csrf
                @method('patch')

                <!-- <div class="updnam">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input  name="name" type="text" class="upname" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div> -->

                <!-- <div class="updemail">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input name="email" type="email" class="pemail" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm mt-2 text-gray-800">
                                    {{ __('Your email address is unverified.') }}

                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="updaddr">
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input name="address" type="text" class="uaddre" :value="old('address', $user->address)" autofocus autocomplete="address" />
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div> -->

                <div class="updprfpic">
                    <x-input-label for="profilepic" :value="__('Profile Pic')" />
                    <x-text-input name="profilepic" type="file" class="uppp" autofocus autocomplete="profilepic" />
                    <x-input-error class="mt-2" :messages="$errors->get('profilepic')" />
                </div>

                <div class="updtpsave">
                    <button>{{ __('Save') }}</button>

                    @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 4000)"
                        class="profileupdated">{{ __('Profile Updated') }}</p>
                    @endif
                </div>
            </form>
        </section>

        <section class="updatepswd">
            <h2>
                {{ __('Update Password') }}
            </h2>

            <p>
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>

            <form method="post" action="{{ route('password.update') }}" class="updtpswd">
                @csrf
                @method('put')

                <div class="updtpswdd">
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                    <x-text-input name="current_password" type="password" class="updtpswdinp" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div class="intnewpswd">
                    <x-input-label for="update_password_password" :value="__('New Password')" />
                    <x-text-input name="password" type="password" class="intnewpswdinp" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div class="confirmnewpswd">
                    <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input name="password_confirmation" type="password" class="confirmnewpswdinp" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pswdsvbtn">
                    <button>{{ __('Save') }}</button>

                    @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="profileupdated">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </section>
        <!-- <section class="deleteuserp">
                <div class="deleteuserph">
                    <h2>
                        {{ __('Delete Account') }}
                    </h2>

                    <p>
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                    </p>
                </div>

            <x-danger-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            >{{ __('Delete Account') }}</x-danger-button>

                <form method="post" action="{{ route('profile.destroy') }}" class="deleteuserprf">
                    @csrf
                    @method('delete')

                    <h2>
                        {{ __('Are you sure you want to delete your account?') }}
                    </h2>

                    <p>
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div>
                        <x-input-label for="password" value="{{ __('Password') }}"  />

                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="{{ __('Password') }}"
                        />

                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <button>
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
         </section> -->
    </div>

</main>
@endsection