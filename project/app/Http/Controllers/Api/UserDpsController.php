<?php

namespace App\Http\Controllers\Api;

use App\Models\Currency;
use App\Models\DpsPlan;
use App\Models\InstallmentLog;
use App\Models\Transaction;
use App\Models\UserDps;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\BaseController as controller;

class UserDpsController extends Controller
{
   

    public function index(){
        $data['dps'] = UserDps::whereUserId(auth()->id())->orderby('id','desc')->paginate(10);
        return $this->success($data, '');
    }

    public function running(){
        $data['dps'] = UserDps::whereStatus(1)->whereUserId(auth()->id())->orderby('id','desc')->paginate(10);
        return $this->success($data, '');
    }

    public function matured(){
        $data['dps'] = UserDps::whereStatus(2)->whereUserId(auth()->id())->orderby('id','desc')->paginate(10);
        return $this->success($data, '');
    }

    public function dpsPlan(){
        $data['plans'] = DpsPlan::orderBy('id','desc')->whereStatus(1)->orderby('id','desc')->paginate(12);
        return $this->success($data, '');
    }

    public function planDetails(Request $request, $id){
        $data['data'] = DpsPlan::findOrFail($id);
        return $this->success($data, '');
    }

    public function dpsSubmit(Request $request){
        $user = auth()->user();
        if($user->balance >= $request->per_installment){
            $data = new UserDps();

            $plan = DpsPlan::findOrFail($request->dps_plan_id);
            $data->transaction_no = Str::random(4).time();
            $data->user_id = auth()->id();
            $data->dps_plan_id = $plan->id;
            $data->per_installment = $plan->per_installment;
            $data->installment_interval = $plan->installment_interval;
            $data->total_installment = $plan->total_installment;
            $data->interest_rate = $plan->interest_rate;
            $data->given_installment = 1;
            $data->deposit_amount = $request->deposit_amount;
            $data->matured_amount = $request->matured_amount;
            $data->paid_amount = $request->per_installment;
            $data->status = 1;
            $data->next_installment = Carbon::now()->addDays($plan->installment_interval);
            $data->save();

            $user->decrement('balance',$request->per_installment);

            $log = new InstallmentLog();
            $log->user_id = auth()->id();
            $log->transaction_no = $data->transaction_no;
            $log->type = 'dps';
            $log->amount = $request->per_installment;
            $log->save();

            $trans = new Transaction();
            $trans->email = auth()->user()->email;
            $trans->amount = $request->per_installment;
            $trans->type = "Dps";
            $trans->profit = "minus";
            $trans->txnid = $data->transaction_no;
            $trans->user_id = auth()->id();
            $trans->save();
            return $this->success([], 'DPS application submitted');
        }else{
            return $this->error([0 =>  'You Don,t have sufficient balance']);
        }
    }

    public function log($id){
        $data = array();
        $loan = UserDps::findOrfail($id);
        $data['logs'] = InstallmentLog::whereTransactionNo($loan->transaction_no)->whereUserId(auth()->id())->orderby('id','desc')->orderby('id','desc')->paginate(20);
        $data['currency'] = Currency::whereIsDefault(1)->first();
        return $this->success($data, '');
    }
}
