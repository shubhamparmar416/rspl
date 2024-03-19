<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\BankPlan;
use Auth;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\WithdrawMethod;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Validator;
use App\Http\Controllers\Api\BaseController as controller;

class WithdrawController extends Controller
{
    

  	public function index()
    {
        $withdraws = Withdraw::whereUserId(auth()->id())->orderBy('id','desc')->paginate(10);
        return $this->success($withdraws, '');
    }

    public function create()
    {
        $data['sign'] = Currency::whereIsDefault(1)->first();
        $data['methods'] = WithdrawMethod::whereStatus(1)->orderBy('id','desc')->get();
        return $this->success($data, '');
    }


    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|gt:0',
        ]);

        $user = auth()->user();

        if($user->bank_plan_id === null){
            return $this->error([0 => 'You have to buy a plan to withdraw.']);
        }

        if(now()->gt($user->plan_end_date)){
            return $this->error([0 => 'Plan Date Expired.']);
        }

        $bank_plan = BankPlan::whereId($user->bank_plan_id)->first();
        $dailyWithdraws = Withdraw::whereDate('created_at', '=', date('Y-m-d'))->whereStatus('completed')->sum('amount');
        $monthlyWithdraws = Withdraw::whereMonth('created_at', '=', date('m'))->whereStatus('completed')->sum('amount');

        if($dailyWithdraws > $bank_plan->daily_withdraw){
            return $this->error([0 => 'Daily withdraw limit over.']);
        }

        if($monthlyWithdraws > $bank_plan->monthly_withdraw){
            return $this->error([0 => 'Monthly withdraw limit over.']);
        }

        if(baseCurrencyAmount($request->amount) > $user->balance){
            return $this->error([0 => 'Insufficient Account Balance.']);
        }

        $withdrawcharge = WithdrawMethod::whereMethod($request->methods)->first();
        $charge = $withdrawcharge->fixed;

        $messagefee = (($withdrawcharge->percentage / 100) * $request->amount) + $charge;
        $messagefinal = $request->amount - $messagefee;

        $currency = Currency::whereId($request->currency_id)->first();
        $amountToAdd = $request->amount/$currency->value;

        $amount = $amountToAdd;
        $fee = (($withdrawcharge->percentage / 100) * $amount) + $charge;
        $finalamount = $amount - $fee;

        if($finalamount < 0){
            return $this->error([0 => 'Request Amount should be greater than this '.$amountToAdd.' (USD)']);
        }

        if($finalamount > $user->balance){
            return $this->error([0 => 'Insufficient Balance.']);
        }

        $finalamount = number_format((float)$finalamount,2,'.','');

        $user->balance = $user->balance - $amount;
        $user->update();

        $txnid = Str::random(12);
        $newwithdraw = new Withdraw();
        $newwithdraw['user_id'] = auth()->id();
        $newwithdraw['method'] = $request->methods;
        $newwithdraw['txnid'] = $txnid;

        $newwithdraw['amount'] = $finalamount;
        $newwithdraw['fee'] = $fee;
        $newwithdraw['details'] = $request->details;
        $newwithdraw->save();

        $total_amount = $newwithdraw->amount + $newwithdraw->fee;

        $trans = new Transaction();
        $trans->email = $user->email;
        $trans->amount = $finalamount;
        $trans->type = "Payout";
        $trans->profit = "minus";
        $trans->txnid = $txnid;
        $trans->user_id = $user->id;
        $trans->save();
        return $this->success([],'Withdraw Request Amount : '.$request->amount.' Fee : '.$messagefee.' = '.$messagefinal.' ('.$currency->name.') Sent Successfully.');

    }

    public function details(Request $request, $id){
        $data['data'] = Withdraw::findOrFail($id);
        $data['currency'] = Currency::whereIsDefault(1)->first();
        return $this->success($data, '');
    }
}
