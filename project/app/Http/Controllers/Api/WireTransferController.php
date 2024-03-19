<?php

namespace App\Http\Controllers\Api;

use App\Models\BalanceTransfer;
use App\Models\BankPlan;
use App\Models\WireTransfer;
use App\Models\WireTransferBank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\BaseController as controller;
class WireTransferController extends Controller
{
  

    public function index(){
        $data['transfers'] = WireTransfer::where('user_id',auth()->id())->orderBy('id','desc')->paginate(20);
        return $this->success($data, '');
    }

    public function create(){
        $data['banks'] = WireTransferBank::whereStatus(1)->orderBy('id','desc')->get();
        return $this->success($data, '');
    }

    public function store(Request $request){
        $request->validate([
            'wire_transfer_bank_id' => 'required',
            'currency' => 'required',
            'routing_number' => 'required',
            'country' => 'required',
            'account_number' => 'required',
            'account_holder_name' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();

        if($user->bank_plan_id === null){
            return $this->error([0 => 'You have to buy a plan to withdraw.']);
        }

        if(now()->gt($user->plan_end_date)){
            return $this->error([0 => 'Plan Date Expired.']);
        }

        $bank_plan = BankPlan::whereId($user->bank_plan_id)->first();
        $dailySend = BalanceTransfer::whereUserId(auth()->id())->whereDate('created_at', '=', date('Y-m-d'))->whereStatus(1)->sum('amount');
        $monthlySend = BalanceTransfer::whereUserId(auth()->id())->whereMonth('created_at', '=', date('m'))->whereStatus(1)->sum('amount');

        if($dailySend > $bank_plan->daily_send){
            return $this->error([0 => 'Daily send limit over.']);
        }

        if($monthlySend > $bank_plan->monthly_send){
            return $this->error([0 => 'Monthly send limit over.']);
        }

        if($request->amount > $user->balance){
            return $this->error([0 => 'Insufficient Account Balance.']);
        }

        $data = new WireTransfer();
        $data->transaction_no = Str::random(4).time();
        $data->user_id = auth()->id();
        $data->wire_transfer_bank_id = $request->wire_transfer_bank_id;
        $data->currency = $request->currency;
        $data->routing_number = $request->routing_number;
        $data->country = $request->country;
        $data->swift_code = $request->swift_code;
        $data->account_number = $request->account_number;
        $data->account_holder_name = $request->account_holder_name;
        $data->amount = baseCurrencyAmount($request->amount);
        $data->note = $request->note;
        $data->save();

        $user->decrement('balance',$request->amount);
        return $this->success([0 => 'Wire Transfer Request Sent Successfully.']);
    }

    public function show($id){
        $data = WireTransfer::whereId($id)->first();
        return $this->success($data, '');

    }
}
