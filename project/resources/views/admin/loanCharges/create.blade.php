@extends('layouts.admin')

@section('content')
<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between py-3">
  <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add New Loan Charges') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.loan.charges.index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
  <ol class="breadcrumb m-0 py-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.loan.charges.index')}}">{{ __('Loan Charges') }}</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.loan.charges.create')}}">{{ __('Add Loan Charges') }}</a></li>
  </ol>
  </div>
</div>

<div class="row justify-content-center mt-3">
<div class="col-md-10">
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('Add Loan Charges') }}</h6>
    </div>

    <div class="card-body">
      <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
      <form class="geniusform" action="{{route('admin.loan.charges.store')}}" method="POST" enctype="multipart/form-data">

          @include('includes.admin.form-both')

          {{ csrf_field() }}

          <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('Enter Name') }}" required>
          </div>

          <div class="form-group">
            <label for="gst_applicable">{{ __('GST Applicable') }} ({{$currency->name}})</label> <br>

            <Input type = 'Radio'  id="gst_applicable" Name ='gst_applicable' value= 'YES' > YES

            <Input type = 'Radio'    id="gst_applicable" Name ='gst_applicable' value= 'NO'> NO
          </div>

          <div class="form-group">
            <label for="gst_percentage">{{ __('GST Percentage') }} (%)</label>
            <input type="number" class="form-control" id="gst_percentage" name="gst_percentage" placeholder="{{ __('GST Percentage') }}" required>
          </div>

          <div class="form-group">
            <label for="amt_type gst_applicable">{{ __('Amount Type') }}</label> <br>
             <Input type = 'Radio'  id="amt_type" Name ='amt_type' value= 'percentage' > Percentage

            <Input type = 'Radio'  id="amt_type" Name ='amt_type' value= 'price' > Price
          </div>

          <div class="form-group">
            <label for="amt_value">{{ __('Amount Value') }}</label>
            <input type="number" class="form-control" id="amt_value" name="amt_value" placeholder="{{ __('Amount Value') }}" min="1"  required>
          </div>

          <div class="form-group">
            <label for="status">{{ __('Status') }}</label> <br>
             <Input type = 'Radio'  id="status" Name ='status' value= '1' checked> Active

            <Input type = 'Radio'  id="status" Name ='status' value= '0'> In Active
          </div>

          <div class="form-group">
            <label for="charge_type">{{ __('Charges Type') }}</label>
            <!-- <input type="number" class="form-control" id="installment_interval" name="installment_interval" placeholder="{{ __('Repayment Frequency') }}" min="1" value="" required> -->
            <select class="form-control" id="charge_type" name="charge_type" required="">
              <option value="">Select</option>
              <option value="1" data-id="none"> None</option>
              <option value="2" data-id="loan_sanction">Loan Sanction</option>
              <option value="3" data-id="fee">Fee</option>
              <option value="4" data-id="annual">Annual</option>
              <option value="5" data-id="quaterly">Quaterly</option>
              <option value="6">Half Yearly</option>
              <option value="7">Miscellaneous</option>
            </select>
          </div>  
          
          <div class="form-group">
            <h3 id="profitShow" class="text-center"></h3>
          </div>

          <button type="submit" id="submit-btn" class="btn btn-primary w-100 mt-2">{{ __('Submit') }}</button>

      </form>
    </div>
  </div>
</div>

</div>

@endsection

@section('scripts')
<script type="text/javascript">

</script>
@endsection
