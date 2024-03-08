@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between py-3">
        <h5 class=" mb-0 text-gray-800 pl-3">{{ __('KYC Details') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.kyc.info','user')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:;">{{ __('KYC') }}</a></li>
        </ol>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        @include('includes.admin.form-success')
        <div class="row">
            <div class="col-lg-12">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">
                            {{__('KYC Information')}}
                        </h4>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                @if ($kycInformations != NULL)
                                    @if ($api_response_digilocker != '')
                                    <tr>
                                       <th>KYC Verify</th>
                                       <td>Digilocker</td>
                                   </tr>
                                   <tr>
                                       <th>Name</th>
                                       <td>{{$api_response_digilocker_status->model->name}}</td>
                                   </tr>
                                   <tr>
                                       <th>CareOf</th>
                                       <td>{{$api_response_digilocker_status->model->careOf}}</td>
                                   </tr>
                                   <tr>
                                       <th>Current Address</th>
                                       <td>{{$kycInformations->current_address}}</td>
                                   </tr>
                                   <tr>
                                       <th>Masked Aadhar Number</th>
                                       <td>{{$api_response_digilocker_status->model->maskedAdharNumber}}</td>
                                   </tr>
                                   <tr>
                                       <th>Gender</th>
                                       <td>{{$api_response_digilocker_status->model->gender}}</td>
                                   </tr>
                                   <tr>
                                       <th>DOB</th>
                                       <td>{{$api_response_digilocker_status->model->dob}}</td>
                                   </tr>
                                   <tr>
                                       <th>Digilocker Address</th>
                                       <td>
                                        {{$api_response_digilocker_status->model->address->house}}
                                        {{$api_response_digilocker_status->model->address->street}}
                                        {{$api_response_digilocker_status->model->address->landmark}}
                                        {{$api_response_digilocker_status->model->address->loc}}
                                        {{$api_response_digilocker_status->model->address->po}}
                                        {{$api_response_digilocker_status->model->address->dist}}
                                        {{$api_response_digilocker_status->model->address->subdist}}
                                        {{$api_response_digilocker_status->model->address->vtc}}
                                        {{$api_response_digilocker_status->model->address->pc}}
                                        {{$api_response_digilocker_status->model->address->state}}
                                        {{$api_response_digilocker_status->model->address->country}}
                                        </td>
                                   </tr>
                                   <tr>
                                        <th>Image</th>
                                        <td><img src="data:image/png;base64, {{ $api_response_digilocker_status->model->image }}" alt="Image Preview" style="width: 100%;max-height: 100%;" /></td>
                                    </tr>
                                   @endif

                                   @if ($aadharDetails != '')
                                   <tr>
                                       <th>KYC Verify</th>
                                       <td>Manually uploaded Aadhar, Pan, and Voter</td>
                                   </tr>
                                   <tr>
                                       <th>Current Address</th>
                                       <td>{{$kycInformations->current_address}}</td>
                                   </tr>
                                   <tr>
                                       <th>Aadhar No</th>
                                       <td>{{$kycInformations->aadhaar_no}}<img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" style="position: absolute;"></td>
                                   </tr>
                                   <tr>
                                        <th>Aadhar Image</th>
                                        <td><a href="{{url('project/storage/kyc/').'/'.$kycInformations->aadhaar_front}}" target="_blank"><img src="{{url('project/storage/kyc/').'/'.$kycInformations->aadhaar_front}}" class="img-thumbnail"></a><a href="{{url('project/storage/kyc/').'/'.$kycInformations->aadhaar_back}}" target="_blank"><img src="{{url('project/storage/kyc/').'/'.$kycInformations->aadhaar_back}}" class="img-thumbnail"></a><img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" style="position: absolute;"></td>
                                    </tr>
                                   <tr>
                                       <th>Aadhar State</th>
                                       <td>{{$aadharDetails->result->aadhaar_state}}</td>
                                   </tr>
                                   <tr>
                                       <th>Aadhar Age Band</th>
                                       <td>{{$aadharDetails->result->aadhaar_age_band}}</td>
                                   </tr>
                                   <tr>
                                       <th>Aadhar Status</th>
                                       <td>{{$aadharDetails->result->aadhaar_result}}</td>
                                   </tr>
                                   <tr><th>Pan Image</th><td><a href="{{url('project/storage/kyc/').'/'.$kycInformations->pan_front}}" target="_blank"><img src="{{url('project/storage/kyc/').'/'.$kycInformations->pan_front}}" class="img-thumbnail"></a><a href="{{url('project/storage/kyc/').'/'.$kycInformations->pan_back}}" target="_blank"><img src="{{url('project/storage/kyc/').'/'.$kycInformations->pan_back}}" class="img-thumbnail"></a><img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" style="position: absolute;"></td></tr>
                                   <tr>
                                       <th>Pan Name</th>
                                       <td>{{$panDetails->result->fullname}}</td>
                                   </tr>
                                   <tr>
                                       <th>Pan Number</th>
                                       <td>{{$panDetails->result->pan}}<img src="https://uigaint.com/demo/html/anfraa/kyc-1/assets/images/check-green.svg" style="position: absolute;"></td>
                                   </tr>
                                   <tr>
                                       <th>Pan DOB</th>
                                       <td>{{$panDetails->result->dob}}</td>
                                   </tr>
                                   <tr>
                                       <th>Pan Gender</th>
                                       <td>{{$panDetails->result->gender}}</td>
                                   </tr>
                                   <tr>
                                       <th>Pan Mobile</th>
                                       <td>{{$panDetails->result->mobile}}</td>
                                   </tr>
                                   <tr>
                                       <th>Pan Address</th>
                                       <td>{{$panDetails->result->address->building_name}} {{$panDetails->result->address->street_name}} {{$panDetails->result->address->locality}} {{$panDetails->result->address->pincode}} {{$panDetails->result->address->city}} {{$panDetails->result->address->state}} {{$panDetails->result->address->country}}</td>
                                   </tr>
                                   <tr>
                                       <th>Pan Type</th>
                                       <td>{{$panDetails->result->pan_type}}</td>
                                   </tr>
                                   @endif
                                    
                                @else 
                                    <p class="text-center mt-5">@lang('KYC NOT SUBMITTTED')</p>
                                @endif


                            </tbody>
                        </table>
                    </div>
                    <div class="footer-area">
                        @if ($kycInformations != NULL)
                            @if ($user->kyc_status == 0)
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" data-href="{{ route('admin.user.kyc',['id1' => $user->id, 'id2' => 1]) }}" class="btn btn-primary"><i class="far fa-check-circle"></i> {{__('Approve')}}</a>
                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" data-href="{{ route('admin.user.kyc',['id1' => $user->id, 'id2' => 2]) }}" class="btn btn-danger ml-3"><i class="fas fa-minus-circle"></i> {{__('Reject')}}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


{{-- STATUS MODAL --}}
<div class="modal fade status-modal" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __("Update Status") }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<p class="text-center">{{ __("You are about to change the status.") }}</p>
				<p class="text-center">{{ __("Do you want to proceed?") }}</p>
			</div>

			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</a>
				<a href="javascript:;" class="btn btn-success btn-ok">{{ __("Update") }}</a>
			</div>
		</div>
	</div>
</div>
{{-- STATUS MODAL ENDS --}}



@endsection