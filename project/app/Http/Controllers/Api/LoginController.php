<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as controller;
use App\Models\Generalsetting;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LoginController extends controller
{

  public function login(Request $request)
  {
    $rules = [
      'email' => 'required|email',
      'password' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return $this->error($validator->getMessageBag()->toArray());
    }

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
      if (Auth::user()->is_banned == 1) {
        Auth::revoke();
        return $this->error([0 => 'You are Banned From this system!']);
      }

      if (Auth::user()->email_verified == 'No') {
        Auth::revoke();
        return $this->error([0 => 'Your Email is not Verified!']);
      }
      $gs = Generalsetting::first();
      $user = auth()->user();

      if ($gs->two_factor && $user->twofa) {
        $user->is_two_factor = true;
      } else {
        $user->update(['verified' => 1]);
        $user->is_two_factor = false;

      }

      $token = $user->createToken('MyApp')->accessToken;
      $user->token = $token;
      return $this->success($user, 'User retrived successfully!');
    }
    return $this->error([0 => 'Credentials Doesn\'t Match !']);
  }

  public function logout()
  {
    $token = Auth::user()->token();
    $token->revoke();
    return response()->json(array('code' => 401, 'data' => [], 'errors' => [], 'message' => 'User logged out!'));
  }



}
