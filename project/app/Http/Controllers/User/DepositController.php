<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentGateway;
use App\Models\UserLoan;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['deposits'] = Deposit::orderby('id', 'desc')->whereUserId(auth()->id())->paginate(10);
        return view('user.deposit.index', $data);
    }

    public function create()
    {
        $data['availableGatways'] = ['flutterwave','authorize.net','razorpay','mollie','paytm','instamojo','stripe','paypal','paystack'];
        $data['gateways'] = PaymentGateway::OrderBy('id', 'desc')->whereStatus(1)->get();
        
        return view('user.deposit.create', $data);
    }

    public function add()
    {
        $data['availableGatways'] = ['flutterwave','authorize.net','razorpay','mollie','paytm','instamojo','stripe','paypal','paystack'];
        $data['gateways'] = PaymentGateway::OrderBy('id', 'desc')->whereStatus(1)->get();
        $data['loans'] = UserLoan::whereUserId(auth()->id())->orderby('id', 'desc')->paginate(100);
        $data['id'] = 0;
        return view('user.deposit.create', $data);
    }

    public function addid(Request $request, $id)
    {
        $data['availableGatways'] = ['flutterwave','authorize.net','razorpay','mollie','paytm','instamojo','stripe','paypal','paystack'];
        $data['gateways'] = PaymentGateway::OrderBy('id', 'desc')->whereStatus(1)->get();
        $data['loans'] = UserLoan::whereId($id)->orderby('id', 'desc')->paginate(100);
        $data['id'] = $id;
        return view('user.deposit.create', $data);
    }
}
