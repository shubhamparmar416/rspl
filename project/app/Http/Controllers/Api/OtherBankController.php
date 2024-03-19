<?php

namespace App\Http\Controllers\Api;

use App\Models\BalanceTransfer;
use App\Models\BankPlan;
use App\Models\Beneficiary;
use App\Models\Generalsetting;
use App\Models\OtherBank;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\BaseController as controller;

class OtherBankController extends Controller
{

    public function index()
    {
        $data['beneficiaries'] = Beneficiary::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->paginate(10);
        return $this->success($data, '');
    }

    public function othersend($id)
    {
        $data['data'] = Beneficiary::findOrFail($id);
        return $this->success($data, '');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        $user = auth()->user();
        if ($user->bank_plan_id === null) {
            return $this->error([0 => 'You have to buy a plan to withdraw.']);
        }

        if (now()->gt($user->plan_end_date)) {
            return $this->error([0 => 'Plan Date Expired.']);
        }

        $bank_plan = BankPlan::whereId($user->bank_plan_id)->first();
        $dailySend = BalanceTransfer::whereUserId(auth()->id())->whereDate('created_at', '=', date('Y-m-d'))->whereStatus(1)->sum('amount');
        $monthlySend = BalanceTransfer::whereUserId(auth()->id())->whereMonth('created_at', '=', date('m'))->whereStatus(1)->sum('amount');

        if ($dailySend > $bank_plan->daily_send) {
            return $this->error([0 => 'Daily send limit over.']);
        }

        if ($monthlySend > $bank_plan->monthly_send) {
            return $this->error([0 => 'Monthly send limit over.']);
        }

        $amount = $request->amount;

        $gs = Generalsetting::first();
        $otherBank = OtherBank::whereId($request->other_bank_id)->first();
        $dailyTransactions = BalanceTransfer::whereType('other')->whereUserId(auth()->user()->id)->whereDate('created_at', now())->get();
        $monthlyTransactions = BalanceTransfer::whereType('other')->whereUserId(auth()->user()->id)->whereMonth('created_at', now()->month())->get();

        if ($otherBank) {
            $cost = $otherBank->fixed_charge + ($amount / 100) * $otherBank->percent_charge;
            $finalAmount = $amount + $cost;

            if (convertedAmount($otherBank->min_limit) > $amount) {
                return $this->error([0 => 'Request Amount should be greater than this']);
            }

            if (convertedAmount($otherBank->max_limit) < $amount) {
                return $this->error([0 => 'Request Amount should be less than this']);
            }

            if (convertedAmount($otherBank->daily_maximum_limit) <= $finalAmount) {
                return $this->error([0 => 'Your daily limitation of transaction is over.']);
            }

            if (convertedAmount($otherBank->daily_maximum_limit) <= $dailyTransactions->sum('final_amount')) {
                return $this->error([0 => 'Your daily limitation of transaction is over.']);
            }

            if ($otherBank->daily_total_transaction <= count($dailyTransactions)) {
                return $this->error([0 => 'Your daily number of transaction is over.']);
            }

            if (convertedAmount($otherBank->monthly_maximum_limit) < $monthlyTransactions->sum('final_amount')) {
                return $this->error([0 => 'Your monthly limitation of transaction is over.']);
            }

            if ($otherBank->monthly_total_transaction <= count($monthlyTransactions)) {
                return $this->error([0 => 'Your monthly number of transaction is over!']);
            }

            if ($user->balance < 0 && baseCurrencyAmount($finalAmount) > $user->balance) {
                return $this->error([0 => 'Insufficient Balance!']);
            }

            if (baseCurrencyAmount($amount) > $user->balance) {
                return $this->error([0 => 'Insufficient Account Balance.']);
            }

            $txnid = Str::random(4) . time();

            $data = new BalanceTransfer();
            $data->user_id = auth()->user()->id;
            $data->transaction_no = $txnid;
            $data->other_bank_id = $request->other_bank_id;
            $data->beneficiary_id = $request->beneficiary_id;
            $data->type = 'other';
            $data->cost = baseCurrencyAmount($cost);
            $data->amount = baseCurrencyAmount($amount);
            $data->final_amount = baseCurrencyAmount($finalAmount);
            $data->status = 0;
            $data->save();

            $trans = new Transaction();
            $trans->email = $user->email;
            $trans->amount = baseCurrencyAmount($finalAmount);
            $trans->type = "Send Money";
            $trans->profit = "minus";
            $trans->txnid = $txnid;
            $trans->user_id = $user->id;
            $trans->save();

            $user->decrement('balance', $finalAmount);
            return $this->success([], 'Money Send successfully.');
        }

    }
}
