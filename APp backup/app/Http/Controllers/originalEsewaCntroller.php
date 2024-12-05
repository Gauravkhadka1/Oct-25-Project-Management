<?php

namespace App\Http\Controllers;
use App\Models\Donations;

use Illuminate\Http\Request;
require '../vendor/autoload.php';

use RemoteMerge\Esewa\Client;
use RemoteMerge\Esewa\Config;

class EsewaController extends Controller
{
   public function esewaPay(Request $request){
    $pid = uniqid();
    $email = auth()->user()->email;
    $amount =  $request->amount;
    $psc = $request->tip;


    if($request->isMethod("post")){
        $request->validate([
          "amount" => "required|numeric",
          "tip" => "nullable|numeric"
        ]);

      
      Donations::create([
         "id" =>  auth()->user()->id ,
          "name" =>  auth()->user()->name ,
          "email" =>  auth()->user()->email,
          "donationid" =>  $pid,
          "amount" => $request->amount,
          "tip" => $request->tip,
          "esewa_status" => 'unverified'
          
        ]);
         

        // Set success and failure callback URLs.
        $successUrl = url('/success');
        $failureUrl = url('/failure');

        // Config for development.
        $config = new Config($successUrl, $failureUrl);

        // Config for production.
        // $config = new Config($successUrl, $failureUrl, 'b4e...e8c753...2c6e8b');

        // Initialize eSewa client.
        $esewa = new Client($config);
        $esewa->process($pid, $amount, 0, $psc, 0);
   }
}


   public function esewaPaySuccess(){
    $pid = $_GET['oid'];
    $refId = $_GET['refId'];
    $amount = $_GET['amt'];
    $email = auth()->user()->email;

    $donations = Donations::where('donationid', $pid)->first();
        if ($donations) {
            $update_status = $donations->update([
                'esewa_status' => 'verified',
              
    ]);



    if($update_status){
        $msg = ' Donation Success!';
        $msg1 = 'Thank you for your kind donation';
        return view('thankyou', compact('msg', 'msg1'));
    }
}
   }

   public function esewaPayFailed(){
    $pid = $_GET['pid'];
    // $email = auth()->user()->email;


    $donations = Donations::where('donationid', $pid)->first();

        if ($donations) {
            $update_status = $donations->update([
                'esewa_status' => 'failed',
                // 'updated_at' => Carbon::now(),
    ]);
    if($update_status){
        $msg = 'Donation Failed';
        $msg1 = 'Please try again';
        return view('thankyou', compact('msg', 'msg1'));
    }


   }

   }
}
