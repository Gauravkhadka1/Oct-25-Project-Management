<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\ContactFormMail;

class ContactFormController extends Controller
{
   public function postmessage(Request $request) {

    $request->validate([
        'email' => 'required|email'
    ]);

    $data = [
        'name' => $request-> name,
        'email' => $request-> email,
        'contact' => $request-> contact,
        'message' => $request-> message,
    ];

    Mail::to('gauravkhadka111111@gmail.com') ->send(new ContactFormMail($data));


    return back()->with('msg', 'Thank you. Your message has been sent succesfully.');
   }
}
