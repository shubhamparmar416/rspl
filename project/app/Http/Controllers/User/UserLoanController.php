<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BankPlan;
use App\Models\Currency;
use App\Models\InstallmentLog;
use App\Models\LoanPlan;
use App\Models\LoanCharges;
use App\Models\Transaction;
use App\Models\UserLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserLoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['loans'] = UserLoan::whereUserId(auth()->id())->orderby('id', 'desc')->paginate(10);
        return view('user.loan.index', $data);
    }

    public function pending()
    {
        $data['loans'] = UserLoan::whereStatus(0)->whereUserId(auth()->id())->orderby('id', 'desc')->paginate(10);
        return view('user.loan.pending', $data);
    }

    public function running()
    {
        $data['loans'] = UserLoan::whereStatus(1)->whereUserId(auth()->id())->orderby('id', 'desc')->paginate(10);
        return view('user.loan.running', $data);
    }

    public function paid()
    {
        $data['loans'] = UserLoan::whereStatus(3)->whereUserId(auth()->id())->orderby('id', 'desc')->paginate(10);
        return view('user.loan.paid', $data);
    }

    public function rejected()
    {
        $data['loans'] = UserLoan::whereStatus(2)->whereUserId(auth()->id())->orderby('id', 'desc')->paginate(10);
        return view('user.loan.rejected', $data);
    }

    public function loanPlan()
    {
        $data['plans'] = LoanPlan::orderBy('id', 'desc')->whereStatus(1)->paginate(12);
        return view('user.loan.plan', $data);
    }

    public function loanAmount(Request $request)
    {
        $plan = LoanPlan::whereId($request->planId)->first();

        // Get Sanction loan charges ID
        $sanctionCharges = LoanCharges::where('type','2')->pluck('id')->toArray();
        $planCharges = explode(', ', $plan->loan_charges);

        $array1_lower = array_map('strtolower', $sanctionCharges);
        $array2_lower = array_map('strtolower', $planCharges);

        $planSactionCharges = array_intersect($array1_lower, $array2_lower);

        $planSactionChargesData = LoanCharges::select('amt_type','amt_value')->whereIn('id',$planSactionCharges)->get();

        $amount = $request->amount;
        $otherCharges = 0;
        $min_amount = convertedAmount($plan->min_amount);
        $max_amount = convertedAmount($plan->max_amount);

        if ($amount >= $min_amount && $amount <= $max_amount) {
            $total_installment = $plan->total_installment/12;

           foreach( $planSactionChargesData as $charges) {
              if($charges['amt_type'] && $charges['amt_type'] != NULL) {
                switch($charges['amt_type']) {
                    case 'percentage':
                        $otherCharges += ($amount * $charges['amt_value']) / 100;
                    break;

                    case 'price':
                        $otherCharges +=  $charges['amt_value'];
                    break;
                }
              }
           }

            $data['data'] = $plan;
            $data['loanAmount'] = $amount;
            $data['currency'] = globalCurrency();
            //$data['perInstallment'] = ($amount * $plan->per_installment)/100;
            $data['perInstallment'] = round($amount/$plan->total_installment);
            $totalAmount = $data['perInstallment'] * $plan->total_installment;

            $data['totalAmount'] = $totalAmount + $otherCharges;
            $data['otherCharges'] = $otherCharges;


            return view('user.loan.apply', $data);
        } else {
            return redirect()->back()->with('warning', 'Request Money should be between minium and maximum amount!');
        }
    }

    public function loanRequest(Request $request)
    {

        $user = auth()->user();

        if ($user->bank_plan_id === null) {
            return redirect()->route('user.loans.plan')->with('warning', 'You have to buy a plan to loan');
        }

        if (now()->gt($user->plan_end_date)) {
            return redirect()->route('user.loans.plan')->with('warning', 'Plan Date Expired.');
        }

        $bank_plan = BankPlan::whereId($user->bank_plan_id)->first();
        $monthlyLoans = UserLoan::whereUserId(auth()->id())->whereMonth('created_at', '=', date('m'))->whereStatus('approve')->sum('loan_amount');

        if ($monthlyLoans > $bank_plan->loan_amount) {
            return redirect()->route('user.loans.plan')->with('warning', 'Monthly loan limit over.');
        }

        $data = new UserLoan();
        $input = $request->all();

        $loan = LoanPlan::findOrFail($request->plan_id);

        $requireInformations = [];
        if ($loan->required_information) {
            foreach (json_decode($loan->required_information) as $key => $value) {
                $requireInformations[$value->type][$key] = str_replace(' ', '_', $value->field_name);
            }
        }


        $details = [];
        foreach ($requireInformations as $key => $infos) {
            foreach ($infos as $index => $info) {
                if ($request->has($info)) {
                    if ($request->hasFile($info)) {
                        if ($file = $request->file($info)) {
                            $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
                            $file->move('assets/images', $name);
                            $details[$info] = [$name,$key];
                        }
                    } else {
                        $details[$info] = [$request->$info,$key];
                    }
                }
            }
        }

        if (!empty($details)) {
            $input['required_information'] = json_encode($details, true);
        }

        $txnid = Str::random(4).time();
        $input['transaction_no'] = $txnid;
        $input['user_id'] = auth()->id();
        $input['next_installment'] = now()->addDays($loan->installment_interval);
        $input['given_installment'] = 0;
        $input['paid_amount'] = 0;
        $input['loan_amount'] = baseCurrencyAmount($request->loan_amount);
        $input['per_installment_amount'] = baseCurrencyAmount($request->per_installment_amount);
        $input['total_amount'] = baseCurrencyAmount($request->total_amount);
        $data->fill($input)->save();

        $trans = new Transaction();
        $trans->email = $user->email;
        $trans->amount = baseCurrencyAmount($request->loan_amount);
        $trans->type = "Loan";
        $trans->profit = "plus";
        $trans->txnid = $txnid;
        $trans->user_id = $user->id;
        $trans->save();

        return redirect()->route('user.loans.index')->with('message', 'Loan Requesting Successfully');
    }

    public function log($id)
    {
        $loan = UserLoan::findOrfail($id);
        $logs = InstallmentLog::whereTransactionNo($loan->transaction_no)->whereUserId(auth()->id())->orderby('id', 'desc')->paginate(20);
        $currency = Currency::whereIsDefault(1)->first();

        return view('user.loan.log', compact('logs', 'currency'));
    }

    public function pendingLoanAccept($id)
    {
        $loan = UserLoan::findOrfail($id);
        $loan->message = $loan->message.' -- User Accepted. ';
        $loan->save();
        
        return redirect()->back()->with('sucess', 'Loan sucessfully accepted!');
    }
}
