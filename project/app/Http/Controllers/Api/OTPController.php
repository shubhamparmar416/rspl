<?php

namespace App\Http\Controllers\Api;

use App\Classes\GoogleAuthenticator;
use App\Http\Controllers\Api\BaseController as controller;

use Illuminate\Http\Request;


class OTPController extends Controller
{


  public function otp(Request $request)
  {
    $request->validate([
      'otp' => 'required'
    ]);

    $user = auth()->user();
    $googleAuth = new GoogleAuthenticator();
    $otp = $request->otp;

    $secret = $user->go;
    $oneCode = $googleAuth->getCode($secret);
    $userOtp = $otp;
    if ($oneCode == $userOtp) {
      $user->verified = 1;
      $user->save();
      return $this->success($user, [0 => 'OTP verified successfully!']);
    } else {
      return $this->error([0 => 'OTP not match!']);
    }
  }


}
