<?php

namespace App\Http\Controllers\Api;

use App\Models\Generalsetting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Http\Controllers\Api\BaseController as controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class ForgotController extends Controller
{

  public function forgot(Request $request)
  {
    $gs = Generalsetting::findOrFail(1);
    $input = $request->all();

    if (User::where('email', '=', $request->email)->count() > 0) {

      $admin = User::where('email', '=', $request->email)->firstOrFail();
      $autopass = Str::random(8);
      $input['password'] = bcrypt($autopass);
      $admin->update($input);
      $subject = "Reset Password Request";
      $msg = "Your New Password is : " . $autopass;


      if ($gs->is_smtp == 1) {
        $data = [
          'to' => $request->email,
          'subject' => $subject,
          'body' => $msg,
        ];

        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($data);
      } else {
        $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
        mail($request->email, $subject, $msg, $headers);
      }
      return $this->success([],'Your Password Reseted Successfully. Please Check your email for new Password.');
    } else {
      return $this->error([0 => 'No Account Found With This Email.']);
    }
  }



}
