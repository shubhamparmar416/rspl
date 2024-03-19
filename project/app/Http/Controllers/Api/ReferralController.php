<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as controller;
use Illuminate\Http\Request;
use App\Models\ReferralBonus;
use App\Models\User;

class ReferralController extends Controller
{

    public function referred(){
        $data['referreds'] = User::where('referral_id',auth()->id())->orderBy('id','desc')->paginate(20);
        return $this->success($data, '');
    }
    
    public function commissions(){
        $data['commissions'] = ReferralBonus::where('to_user_id',auth()->id())->orderBy('id','desc')->paginate(20);
        return $this->success($data, '');
    }
}
