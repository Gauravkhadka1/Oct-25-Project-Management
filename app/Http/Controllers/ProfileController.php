<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;



class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

     public function dashboard () {
        // return view ("frontends.welcome");
   
        $userEmail = auth()->user()->email;
        $user = User::where('email', $userEmail)->firstOrFail();
        $username = auth()->user()->username;
        $address = auth() ->user()->address;
       

        // Assuming 'amount' is the donation column in 'campaign1' and 'campaign2'
        $totalDonations = DB::table('campaign1')->where('email', $userEmail)->sum('amount') +
                          DB::table('campaign2')->where('email', $userEmail)->sum('amount');
    
        // Assuming 'tip' is the tip column in 'campaign1' and 'campaign2'
        $totalTips = DB::table('campaign1')->where('email', $userEmail)->sum('tip') +
                     DB::table('campaign2')->where('email', $userEmail)->sum('tip');

                     $totalDonationnum = DB::table('campaign1')->where('email', $userEmail)->count() +
                     DB::table('campaign2')->where('email', $userEmail)->count();



        $donations1 = DB::table('campaign1')->where('email', $userEmail)
        ->orderBy('created_at', 'desc')
        ->get(['created_at', 'amount', 'image', 'a', 'heading', 'total-raised', 'progress-percentage', 'raised-percentage']);

                     // Retrieve donations from Campaign2
        $donations2 = DB::table('campaign2')->where('email', $userEmail)
        ->orderBy('created_at', 'desc')
        ->get(['created_at', 'amount', 'image', 'a', 'heading', 'total-raised', 'progress-percentage', 'raised-percentage']);
             
                     // Combine donations
                     $donations = $donations1->merge($donations2);

                     $donations = $donations->sortByDesc('created_at');
 
        return view('frontends.dashboard', compact('totalDonations', 'totalTips', 'donations','username', 'totalDonationnum', 'address','userEmail', 'user'));
    }



    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
    $user = $request->user();
    $data = $request->validated();

    if ($request->hasFile('profilepic')) {
        // Delete old profile picture if exists
        if ($user->profilepic) {
            Storage::delete($user->profilepic);
        }

        // Store new profile picture
        $data['profilepic'] = $request->file('profilepic')->store('profilepics', 'public');
    }
  

    $user->fill($data);

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');


    }


    public function show($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $userFirstName = $user->name;
        $bio = $user->bio;
        $address = $user->address;

        $totalDonationnum = DB::table('campaign1')->where('email', $email)->count() +
        DB::table('campaign2')->where('email', $email)->count();
       

        // Assuming 'amount' is the donation column in 'campaign1' and 'campaign2'
        $totalDonations = DB::table('campaign1')->where('email', $email)->sum('amount') +
                          DB::table('campaign2')->where('email', $email)->sum('amount');
    
        // Assuming 'tip' is the tip column in 'campaign1' and 'campaign2'
        $totalTips = DB::table('campaign1')->where('email', $email)->sum('tip') +
                     DB::table('campaign2')->where('email', $email)->sum('tip');


    // Retrieve donations from Campaign1
        $donations1 = DB::table('campaign1')->where('email', $email)
        ->orderBy('created_at', 'desc')
        ->get(['created_at', 'amount', 'image', 'a', 'heading', 'total-raised', 'progress-percentage', 'raised-percentage']);

                     // Retrieve donations from Campaign2
        $donations2 = DB::table('campaign2')->where('email', $email)
        ->orderBy('created_at', 'desc')
        ->get(['created_at', 'amount', 'image', 'a', 'heading', 'total-raised', 'progress-percentage', 'raised-percentage']);
             
                     // Combine donations
                     $donations = $donations1->merge($donations2);
                     $donations = $donations->sortByDesc('created_at');
        return view('frontends.dashboard', compact('user', 'totalDonations', 'totalTips', 'donations','userFirstName', 'totalDonationnum', 'bio', 'address'));
    }






    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function index()
{
    $user = auth()->user(); // or any other logic to get user
    return view('frontends.layouts.header', compact('user'));
}

}

