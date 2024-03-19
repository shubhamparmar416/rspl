@extends('layouts.admin')

@section('content')

<div class="content-area">
  <div class="card">
    <div class="d-sm-flex align-items-center justify-content-between">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Template') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{ url()->previous() }}"><i class="fas fa-arrow-left"></i> {{ __("Back") }}</a></h5>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">{{ __('Email Settings') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.mail.edit',$data->id) }}">{{ __('Edit Template') }}</a></li>
    </ol>
    </div>
  </div>
</div>

  <div class="card mb-4 mt-3">
    <div class="card-header py-3 text-center">
      <div class="row" >
        <div class="col-lg-12 offset-lg-4 col-md-12 offset-md-4">
        <p>{{ __('Use the BB codes, it show the data dynamically in your emails.') }}</p>
        <br>
        <table class="table table-bordered">
            <thead>
              <tr>
                <th>{{ __('Meaning') }}</th>
                <th>{{ __('BB Code') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ __('Customer Name') }}</td>
                <td>{customer_name}</td>
              </tr>
              <tr>
                <td>{{ __('Order Amount') }}</td>
                <td>{order_amount}</td>
              </tr>
              <tr>
                <td>{{ ('Admin Name') }}</td>
                <td>{admin_name}</td>
              </tr>
              <tr>
                <td>{{ __('Admin Email') }}</td>
                <td>{admin_email}</td>
              </tr>
              <tr>
                <td>{{ __('Website Title') }}</td>
                <td>{website_title}</td>
              </tr>
              <tr>
                <td>{{ __('Order Number') }}</td>
                <td>{order_number}</td>
              </tr>
            </tbody>
        </table>
        </div>
        </div>
    </div>

    <div class="card-body">
      <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" id="messageTemplate" action="{{route('admin.mail.update',$data->id)}}" method="POST" enctype="multipart/form-data">
          @include('includes.admin.form-both')
          {{ csrf_field() }}

          <div class="form-group">
            <label>{{ __('Email Type') }} *</label>
            <input type="text" class="input-field" placeholder="{{ __('Email Type') }}" required="" value="{{$data->email_type}}" disabled="">
          </div>

          <div class="form-group">
            <label>{{ __('Email Subject') }} *</label>
            <small>{{ __('(In Any Language)') }}</small>
            <input type="text" class="input-field" name="email_subject" placeholder="{{ __('Email Subject') }}" required="" value="{{$data->email_subject}}">
          </div>

          <div id="error-message" style="color: red;"></div>

          <div class="form-group">
            <label>{{ __('Email Body') }} *</label>
            <small>{{ __('(In Any Language)') }}</small>
            <textarea class="form-control summernote" id="email_body" name="email_body" placeholder="{{ __('Email Body') }}">{{ $data->email_body }}</textarea>
          </div>

           <div class="form-group">
            <label>{{ __('SMS Body') }} *</label>
            <small>{{ __('(In Any Language)') }}</small> <input type="checkbox" id="smsCheckbox"> Same as Email Body
            <textarea class="form-control summernote" id="sms_body" name="sms_body" placeholder="{{ __('SMS Body') }}">{{ $data->sms_body }}</textarea>
          </div>

          <div class="form-group">
            <label>{{ __('Whatsapp Body') }} *</label>
            <small>{{ __('(In Any Language)') }}</small> <input type="checkbox" id="whatsappCheckbox"> Same as Email Body
            <textarea class="form-control summernote" id="whatsapp_body" name="whatsapp_body" placeholder="{{ __('Whatsapp Body') }}">{{ $data->whatsapp_body }}</textarea>
          </div>

          <button type="button" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

      </form>
    </div>
  </div>

@endsection
@section('scripts')
<script type="text/javascript">
  'use strict';
  $(document).ready(function() {

      $('#smsCheckbox').change(function() {
          if ($(this).prop('checked')) {
            $('#sms_body').summernote('code', $('#email_body').val());
          } else {
            $('#sms_body').summernote('code', '');

          }
      });

      $('#whatsappCheckbox').change(function() {
          if ($(this).prop('checked')) {
            $('#whatsapp_body').summernote('code', $('#email_body').val());
          } else {
            $('#whatsapp_body').summernote('code', '');

          }
      });

      $('#submit-btn').click(function(event) {
            event.preventDefault();
            // Get the content of Summernote editor
            var email_body = $('#email_body').summernote('code');
            var whatsapp_body = $('#whatsapp_body').summernote('code');
            var sms_body = $('#sms_body').summernote('code');

            var emailContent = email_body.replace(/<[^>]+>/g, '').trim();
            var whatsappContent = whatsapp_body.replace(/<[^>]+>/g, '').trim();
            var smsContent = sms_body.replace(/<[^>]+>/g, '').trim();
            // Check if all the content is empty
            if (!emailContent && !whatsappContent && !smsContent) {
                $('#error-message').text('One of the message template is mandatory.');
            } else {
                // Clear error message if there was one
                $('#error-message').text('');
                // Submit the form
                $('#messageTemplate').submit();
            }
      });

  });

</script>
@endsection