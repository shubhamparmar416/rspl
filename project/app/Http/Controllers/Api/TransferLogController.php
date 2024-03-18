<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as controller;
use App\Models\BalanceTransfer;
use Illuminate\Http\Request;

class TransferLogController extends Controller
{
   
    public function index(){
        $data['logs'] = BalanceTransfer::whereUserId(auth()->id())->orderBy('id','desc')->paginate(10);
        return $this->success($data, '');
    }
}
