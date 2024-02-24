@extends('layouts.user')

@push('css')

@endpush

@section('contents')
<div class="container-xl">
    <div class="page-header d-print-none">
      <div class="row align-items-center">
        <div class="col">
          <h2 class="page-title">
            {{__('Repayment Now')}}
          </h2>
        </div>
      </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card p-3 p-sm-4 p-lg-5">
                    @includeIf('includes.flash')
                    <form class="deposit-form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label required">{{__('Select Loan')}}</label>
                            <select name="loan_plan" id="loan_plan" class="form-select" required>
                                <option value="">{{ __('Select Payment Method') }}</option>
                                @foreach ($loans as $loan)
                                    @if ($loan->status != 2)
                                        <option value="{{$loan->id}}" data-id="{{$loan->per_installment_amount}}" {{$loan->id == $id ? 'selected' : ''}}>{{$loan->plan->title}} ({{ $loan->transaction_no }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label required" style="display: none;">{{__('Payment Method')}}</label>
                            <!-- <select name="method" id="withmethod" class="form-select" hidden="" required>
                                <option value="">{{ __('Select Payment Method') }}</option>
                                @foreach ($gateways as $gateway)
                                    @if ($gateway->type == 'manual')
                                        <option value="Manual" data-details="{{$gateway->details}}">{{ $gateway->title }}</option>
                                    @endif
                                    @if (in_array($gateway->keyword,$availableGatways))
                                        <option value="{{$gateway->keyword}}">{{ $gateway->name }}</option>
                                    @endif
                                @endforeach
                            </select> -->
                            <select name="method" id="withmethod" class="form-select" hidden="" required>
                                <option value="razorpay">Razorpay</option>
                            </select>
                        </div>

                        <div class="col-lg-12 mt-4 manual-payment d-none">
                            <div class="card">
                              <div class="card-body">
                                <div class="row">

                                  <div class="col-lg-12 pb-2 manual-payment-details">
                                  </div>

                                  <div class="col-lg-12">
                                    <label class="form-label required">@lang('Transaction ID')#</label>
                                    <input class="form-control" name="txn_id4" type="text" placeholder="Transaction ID#" id="manual_transaction_id">
                                  </div>

                                </div>
                              </div>
                            </div>
                          </div>

                        <div id="card-view" class="col-lg-12 pt-3 d-none">
                            <div class="row">
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="no_note" value="1">
                                <input type="hidden" name="lc" value="UK">
                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">

                                <div class="col-lg-6 mb-3">
                                    <input type="text" class="form-control card-elements" name="cardNumber" placeholder="{{ __('Card Number') }}" autocomplete="off" required autofocus oninput="validateCard(this.value);"/>
                                    <span id="errCard"></span>
                                </div>

                                <div class="col-lg-6 cardRow mb-3">
                                    <input type="text" class="form-control card-elements" placeholder="{{ ('Card CVC') }}" name="cardCVC" oninput="validateCVC(this.value);">
                                    <span id="errCVC"></span>
                                </div>

                                <div class="col-lg-6">
                                    <input type="text" class="form-control card-elements" placeholder="{{ __('Month') }}" name="month" >
                                </div>

                                <div class="col-lg-6">
                                    <input type="text" class="form-control card-elements" placeholder="{{ __('Year') }}" name="year">
                                </div>

                            </div>
                        </div>

                        <input type="hidden" name="currency_sign" value="{{ $defaultCurrency->sign }}">
                        <input type="hidden" id="currencyCode" name="currency_code" value="{{ $defaultCurrency->name }}">
                        <input type="hidden" name="currency_id" value="{{ $defaultCurrency->id }}">
                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                        <input type="hidden" id="ref_id" name="ref_id" value="">
                        <input type="hidden" name="paystackInfo" id="paystackInfo" value="{{ $paystackKey }}">

                        <div class="form-group mb-3 mt-3">
                            <label class="form-label required">{{__('Payment Amount')}}</label>
                            <input name="amount" id="amount" readonly="true" class="form-control" autocomplete="off" placeholder="{{__('0.0')}}" type="number" value="" min="1" required>
                        </div>

                        <div class="form-group mb-3 ">
                            <label class="form-label">{{__('Description')}}</label>
                            <textarea name="details" class="form-control nic-edit" cols="30" rows="5" placeholder="{{__('Receive account details')}}"></textarea>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">{{__('Submit')}}</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')

<script type="text/javascript">
$(document).ready( function(){
    var dataid = $("#loan_plan option:selected").attr('data-id');
    $("#amount").val(dataid);
});
$('#loan_plan').on("change",function(){
    var dataid = $("#loan_plan option:selected").attr('data-id');
    $("#amount").val(dataid);
});
$(document).ready(function() {
    $('.deposit-form').prop('action','{{ route('deposit.razorpay.submit') }}');
    $('.deposit-form').prop('id','');
    $('#card-view').addClass('d-none');
    $('.card-elements').prop('required',false);
    $('#manual_transaction_id').prop('required',false);
    $('.manual-payment').addClass('d-none');
});
'use strict';

$(document).on('change','#withmethod',function(){
	var val = $(this).val();

	if(val == 'stripe')
	{
		$('.deposit-form').prop('action','{{ route('deposit.stripe.submit') }}');
        $('.deposit-form').prop('id','');
		$('#card-view').removeClass('d-none');
		$('.card-elements').prop('required',true);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
	}

    if(val == 'flutterwave')
	{
		$('.deposit-form').prop('action','{{ route('deposit.flutter.submit') }}');
        $('.deposit-form').prop('id','');
        $('#card-view').addClass('d-none');
        $('.card-elements').prop('required',false);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
	}

    if(val == 'authorize.net')
	{
		$('.deposit-form').prop('action','{{ route('deposit.authorize.submit') }}');
        $('.deposit-form').prop('id','');
		$('#card-view').removeClass('d-none');
		$('.card-elements').prop('required',true);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
	}

    if(val == 'paypal') {
        $('.deposit-form').prop('action','{{ route('deposit.paypal.submit') }}');
        $('.deposit-form').prop('id','');
        $('#card-view').addClass('d-none');
        $('.card-elements').prop('required',false);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
    }

    if(val == 'mollie') {
        $('#deposit-form').prop('action','{{ route('deposit.molly.submit') }}');
        $('.deposit-form').prop('id','');
        $('#card-view').addClass('d-none');
        $('.card-elements').prop('required',false);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
    }


	if(val == 'paytm') {
        $('.deposit-form').prop('action','{{ route('deposit.paytm.submit') }}');
        $('.deposit-form').prop('id','');
        $('#card-view').addClass('d-none');
        $('.card-elements').prop('required',false);
        $('#manual_transaction_id').prop('required',false);

        $('.manual-payment').addClass('d-none');
    }

    if(val == 'paystack') {
        $('.deposit-form').prop('action','{{ route('deposit.paystack.submit') }}');
        $('.deposit-form').prop('id','step1-form');
        $('#card-view').addClass('d-none');
        $('.card-elements').prop('required',false);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
    }

    if(val == 'instamojo') {
        $('.deposit-form').prop('action','{{ route('deposit.instamojo.submit') }}');
        $('.deposit-form').prop('id','');
        $('#card-view').addClass('d-none');
        $('.card-elements').prop('required',false);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
    }

    if(val == 'razorpay') {
        $('.deposit-form').prop('action','{{ route('deposit.razorpay.submit') }}');
        $('.deposit-form').prop('id','');
        $('#card-view').addClass('d-none');
        $('.card-elements').prop('required',false);
        $('#manual_transaction_id').prop('required',false);
        $('.manual-payment').addClass('d-none');
    }

    if(val == 'Manual'){
      $('.deposit-form').prop('action','{{route('deposit.manual.submit')}}');
      $('.deposit-form').prop('id','');
      $('.manual-payment').removeClass('d-none');
      $('#card-view').addClass('d-none');
      $('.card-elements').prop('required',false);
      $('#manual_transaction_id').prop('required',true);
      const details = $(this).find(':selected').data('details');
      $('.manual-payment-details').empty();
      $('.manual-payment-details').append(`<font size="3">${details}</font>`)
    }

});
</script>



<script>
closedFunction=function() {
        alert('Payment Cancelled!');
    }

     successFunction=function(transaction_id) {
        window.location.href = '{{ url('order/payment/return') }}?txn_id=' + transaction_id;
    }

     failedFunction=function(transaction_id) {
         alert('Transaction was not successful, Ref: '+transaction_id)
    }
</script>

    <script>
    'use strict';
    $(document).on('submit','#step1-form',function(){

        var total = parseFloat( $('#amount').val());
        var paystackInfo = $('#paystackInfo').val();
        var curr = $('#currencyCode').val();
        total = Math.round(total);

        var handler = PaystackPop.setup({
        key: paystackInfo,
        email: $('input[name=email]').val(),
        amount: total * 100,
        currency: curr,
        ref: ''+Math.floor((Math.random() * 1000000000) + 1),
        callback: function(response){
            $('#ref_id').val(response.reference);
            $('#step1-form').prop('id','');
            $('.deposit-form').submit();
        },
        onClose: function(){
            window.location.reload();
        }
        });
        handler.openIframe();
            return false;

    });

</script>


  <script type="text/javascript" src="{{ asset('assets/front/js/payvalid.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/front/js/paymin.js') }}"></script>
  <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript" src="{{ asset('assets/front/js/payform.js') }}"></script>


  <script type="text/javascript">
  'use strict';

    var cnstatus = false;
    var dateStatus = false;
    var cvcStatus = false;

    function validateCard(cn) {
      cnstatus = Stripe.card.validateCardNumber(cn);
      if (!cnstatus) {
        $("#errCard").html('Card number not valid<br>');
      } else {
        $("#errCard").html('');
      }
      btnStatusChange();


    }

    function validateCVC(cvc) {
      cvcStatus = Stripe.card.validateCVC(cvc);
      if (!cvcStatus) {
        $("#errCVC").html('CVC number not valid');
      } else {
        $("#errCVC").html('');
      }
      btnStatusChange();
    }

  </script>

    <script src="https://js.paystack.co/v1/inline.js"></script>

@endpush
