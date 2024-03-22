<?php

namespace App\Http\Controllers\Deposit;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Generalsetting;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use App\Models\UserLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('razorpay')->first();
        $paydata = $data->convertAutoData();
        $this->keyId = $paydata['key'];
        $this->keySecret = $paydata['secret'];
        $this->displayCurrency = 'INR';
        $this->api = new Api($this->keyId, $this->keySecret);
    }


    public function store(Request $request)
    {
        if ($request->currency_code != "INR") {
            return redirect()->back()->with('warning', 'Please Select INR Currency For Rezorpay.');
        }

        $settings = Generalsetting::findOrFail(1);
        $deposit = new Deposit();

        $input = $request->all();
        $item_name = $settings->title." Deposit";
        $item_number = Str::random(12);
        $item_amount = $request->amount;

        $loan_plan = $request->loan_plan;
        $userLoanDtl = UserLoan::where('user_id', auth()->user()->id)->where('id', $loan_plan)->first();
        if ($userLoanDtl->per_installment_amount != $item_amount) {
            echo "<script>";
            echo "alert('Amount is not matched. Please try again!');";
            echo "</script>";
            return redirect('/user/repayment/add');
            die;
        }

        $order['item_name'] = $item_name;
        $order['item_number'] = $item_number;
        $order['item_amount'] = round($item_amount, 2);
        $cancel_url = route('user.dashboard');
        $notify_url = route('deposit.razorpay.notify');


        $orderData = [
            'receipt'         => $order['item_number'],
            'amount'          => $order['item_amount'] * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $this->api->order->create($orderData);

        $input['user_id'] = auth()->user()->id;

        Session::put('input_data', $input);
        Session::put('order_data', $order);
        Session::put('order_payment_id', $razorpayOrder['id']);

        $displayAmount = $amount = $orderData['amount'];

        if ($this->displayCurrency !== 'INR') {
            $url = "https://api.fixer.io/latest?symbols=$this->displayCurrency&base=INR";
            $exchange = json_decode(file_get_contents($url), true);

            $displayAmount = $exchange['rates'][$this->displayCurrency] * $amount / 100;
        }

        $checkout = 'automatic';

        if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true)) {
            $checkout = $_GET['checkout'];
        }

        $data = [
            "key"               => $this->keyId,
            "amount"            => $amount,
            "name"              => $order['item_name'],
            "description"       => $order['item_name'],
            "prefill"           => [
                "name"              => $request->customer_name,
                "email"             => $request->customer_email,
                "contact"           => $request->customer_phone,
            ],
            "notes"             => [
                "address"           => $request->customer_address,
                "merchant_order_id" => $order['item_number'],
            ],
            "theme"             => [
                "color"             => "{{$settings->colors}}"
            ],
            "order_id"          => $razorpayOrder['id'],
        ];

        if ($this->displayCurrency !== 'INR') {
            $data['display_currency']  = $this->displayCurrency;
            $data['display_amount']    = $displayAmount;
        }

        $json = json_encode($data);
        $displayCurrency = $this->displayCurrency;

        return view('frontend.razorpay-checkout', compact('data', 'displayCurrency', 'json', 'notify_url'));
    }

    public function notify(Request $request)
    {
        $input = Session::get('input_data');
        $order_data = Session::get('order_data');
        $input_data = $request->all();

        $payment_id = Session::get('order_payment_id');

        $success = true;

        if (empty($input_data['razorpay_payment_id']) === false) {
            try {
                $attributes = array(
                    'razorpay_order_id' => $payment_id,
                    'razorpay_payment_id' => $input_data['razorpay_payment_id'],
                    'razorpay_signature' => $input_data['razorpay_signature']
                );

                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
            }
        }

        if ($success === true) {
            $currency = Currency::where('id', $input['currency_id'])->first();
            $amountToAdd = $input['amount']/$currency->value;

            $deposit = new Deposit();
            $deposit['loan_plan_id'] = $loan_plan;
            $deposit['deposit_number'] = $order_data['item_number'];
            $deposit['user_id'] = auth()->user()->id;
            $deposit['currency_id'] = $request->currency_id;
            $deposit['amount'] = $amountToAdd;
            $deposit['method'] = $input['method'];
            $deposit['status'] = "complete";
            $deposit['txnid'] = $payment_id;
            $deposit->save();

            $loan_plan = $input['loan_plan'];
            $userLoanDtl = UserLoan::where('user_id', auth()->user()->id)->where('id', $loan_plan)->first();
            
            //dd($userLoanDtl);
            $given_installment = $userLoanDtl->given_installment + 1;
            $paid_amount = $userLoanDtl->paid_amount + $amountToAdd;
            $userLoanDtl->given_installment = $given_installment;

            if($userLoanDtl->next_installment != NULL) {

                $userLoanDtl->prev_installment = $userLoanDtl->next_installment;
                $userLoanDtl->next_installment = $userLoanDtl->next_installment->addDays($userLoanDtl->plan->installment_interval);
            }
            
            $userLoanDtl->paid_amount = $paid_amount;
            $userLoanDtl->save();

            $gs =  Generalsetting::findOrFail(1);

            $user = auth()->user();
            $user->balance += $amountToAdd;
            $user->save();

            $trans = new Transaction();
            $trans->email = $user->email;
            $trans->amount = $amountToAdd;
            $trans->type = "Deposit";
            $trans->profit = "plus";
            $trans->txnid = $deposit->deposit_number;
            $trans->user_id = $user->id;
            $trans->save();

            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $user->email,
                    'type' => "Deposit",
                    'cname' => $user->name,
                    'oamount' => $input['amount'],
                    'aname' => "",
                    'aemail' => "",
                    'wtitle' => "",
                ];

                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($data);
            } else {
                $to = $user->email;
                $subject = " You have deposited successfully.";
                $msg = "Hello ".$user->name."!\nYou have invested successfully.\nThank you.";
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($to, $subject, $msg, $headers);
            }



            return redirect()->route('user.dashboard')->with('success', 'Payment amount '.$input['amount'].' ('.$input['currency_code'].') successfully!');
        }
        return redirect()->back()->with('warning', 'Something Went wrong!');
    }
}
