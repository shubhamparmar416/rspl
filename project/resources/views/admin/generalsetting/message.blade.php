@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between py-3">
        <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Message Integration') }} </h5>
        <ol class="breadcrumb py-0 m-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.gs.message') }}">{{ __('Message Integration') }}</a></li>
        </ol>
    </div>
</div>

<div class="card mb-4 mt-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Message Integration') }}</h6>
    </div>

    <div class="card-body">
        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" action="{{route('admin.gs.message.update')}}" method="POST" enctype="multipart/form-data">

            @include('includes.admin.form-both')

            {{ csrf_field() }}

	        <div class="row">
	            <div class="col-md-6">
	              <div class="form-group">
	                <div class="custom-control custom-switch">
	                  <input type="checkbox" name="sms" value="1" {{ ($data->sms == 1) ? 'checked' : '' }} class="custom-control-input" id="sms">
	                  <label class="custom-control-label" for="sms">{{__('SMS')}}</label>
	                  </div>
	              </div>
	            </div>

	            <div class="col-md-6">
	                <div class="form-group">
	                  <div class="custom-control custom-switch">
	                    <input type="checkbox" name="whatsapp" value="1" {{ ($data->whatsapp == 1) ? 'checked' : '' }} class="custom-control-input" id="whatsapp">
	                    <label class="custom-control-label" for="whatsapp">{{__('Whatsapp')}}</label>
	                    </div>
	                </div>
	            </div>

	            <div class="col-md-6">
	                <div class="form-group">
	                  <div class="custom-control custom-switch">
	                    <input type="checkbox" name="email" value="1" {{ ($data->email == 1) ? 'checked' : '' }} class="custom-control-input" id="email">
	                    <label class="custom-control-label" for="email">{{__('Email')}}</label>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-md-6">
	            	<button type="submit" id="submit-btn" class="btn btn-primary w-100 mt-2">{{ __('Submit') }}</button>
	            </div>

	        </div>

	    </form>
	</div>
</div>

@endsection
