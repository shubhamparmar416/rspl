<?php

namespace App\Http\Controllers\Api;

use App\Models\BankPlan;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as controller;

class PricingPlanController extends controller
{
   

    public function index()
    {
        $data['packages'] = BankPlan::all();
        return $this->success($data, '');
    }

    public function subscription($id)
    {
        $data['data'] = BankPlan::findOrFail($id);
        $data['availableGatways'] = ['flutterwave', 'authorize.net', 'razorpay', 'mollie', 'paytm', 'instamojo', 'stripe', 'paypal'];
        $data['gateways'] = PaymentGateway::OrderBy('id', 'desc')->whereStatus(1)->get();
        return $this->success($data, '');

    }
}
