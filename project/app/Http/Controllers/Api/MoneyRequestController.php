<?php

namespace App\Http\Controllers\Api;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Api\BaseController as controller;
use App\Models\BankPlan;
use App\Models\Generalsetting;
use App\Models\MoneyRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MoneyRequestController extends Controller
{

    public function index(){
        $data['requests'] = MoneyRequest::orderby('id','desc')->whereUserId(auth()->id())->paginate(10);
        return $this->success($data, '');

    }

    public function receive(){
        $data['requests'] = MoneyRequest::orderby('id','desc')->whereReceiverId(auth()->id())->paginate(10);
        return $this->success($data, '');
    }

    // public function create(){
    //     return view('user.requestmoney.create');
    // }

    public function store(Request $request){
        $request->validate([
            'account_name' => 'required',
            'amount' => 'required|gt:0',
        ]);

        $user = auth()->user();
        $amount = baseCurrencyAmount($request->amount);

        if($user->bank_plan_id === null){
            return $this->error([0 => 'You have to buy a plan to withdraw.']);
        }

        if(now()->gt($user->plan_end_date)){
            return $this->error([0 => 'Plan Date Expired.']);
        }

        $bank_plan = BankPlan::whereId($user->bank_plan_id)->first();
        $dailyRequests = MoneyRequest::whereUserId(auth()->id())->whereDate('created_at', '=', date('Y-m-d'))->whereStatus('success')->sum('amount');
        $monthlyRequests = MoneyRequest::whereUserId(auth()->id())->whereMonth('created_at', '=', date('m'))->whereStatus('success')->sum('amount');

        $gs = Generalsetting::first();

        if($request->account_number == $user->account_number){
            return $this->error([0 => 'You can not send money yourself!']);
        }

        $user = User::where('account_number',$request->account_number)->first();
        if($user === null){
            return $this->error([0 => 'No register user with this email!']);
        }

        if($dailyRequests > $bank_plan->daily_receive){
            return $this->error([0 => 'Daily request limit over.']);
        }

        if($monthlyRequests > $bank_plan->monthly_receive){
            return $this->error([0 => 'Monthly request limit over.']);
        }

        $cost = $gs->fixed_request_charge + ($amount/100) * $gs->percentage_request_charge;
        $finalAmount = $amount + $cost;


        $receiver = User::where('account_number',$request->account_number)->first();

        $txnid = Str::random(4).time();

        $data = new MoneyRequest();
        $data->user_id = auth()->user()->id;
        $data->receiver_id = $receiver->id;
        $data->receiver_name = $receiver->name;
        $data->transaction_no = $txnid;
        $data->cost = $cost;
        $data->amount = $amount;
        $data->status = 0;
        $data->details = $request->details;
        $data->save();

        $trans = new Transaction();
        $trans->email = $user->email;
        $trans->amount = $finalAmount;
        $trans->type = "Request Money";
        $trans->profit = "plus";
        $trans->txnid = $txnid;
        $trans->user_id = $user->id;
        $trans->save();
        return $this->success([],'Request Money Send Successfully.');

    }

    public function send($id){
        $data = MoneyRequest::findOrFail($id);
        $gs = Generalsetting::first();

        $sender = User::whereId($data->receiver_id)->first();
        $receiver = User::whereId($data->user_id)->first();


        if($data->amount > $sender->balance){
            return $this->error([0 => 'You don,t have sufficient balance!']);
        }

        $finalAmount = $data->amount - $data->cost;

        $sender->decrement('balance',$data->amount);
        $receiver->increment('balance',$finalAmount);

        $data->update(['status'=>1]);

        $trans = new Transaction();
        $trans->email = auth()->user()->email;
        $trans->amount = $data->amount;
        $trans->type = "Request Money";
        $trans->profit = "minus";
        $trans->txnid = $data->transaction_no;
        $trans->user_id = auth()->id();
        $trans->save();

        $trans = new Transaction();
        $trans->email = $receiver->email;
        $trans->amount = $data->amount;
        $trans->type = "Request Money";
        $trans->profit = "plus";
        $trans->txnid = $data->transaction_no;
        $trans->user_id = $receiver->id;
        $trans->save();

        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => $receiver->email,
                'type' => "Request money",
                'cname' => $receiver->name,
                'oamount' => $finalAmount,
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($data);
        }
        else
        {
            $to = $receiver->email;
            $subject = " Money send successfully.";
            $msg = "Hello ".$receiver->name."!\nMoney send successfully.\nThank you.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($to,$subject,$msg,$headers);
        }
        return $this->success([],'Successfully Money Send.');
    }

    public function details($id){
        $data = MoneyRequest::findOrFail($id);
        $from = User::whereId($data->user_id)->first();
        $to = User::whereId($data->receiver_id)->first();
        return view('user.requestmoney.details',compact('data','from','to'));
    }
}
