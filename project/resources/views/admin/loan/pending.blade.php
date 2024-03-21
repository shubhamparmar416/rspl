@extends('layouts.admin')

@section('content')

<div class="card">
	<div class="d-sm-flex align-items-center justify-content-between py-3">
	<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Pending Loans') }}</h5>
	<ol class="breadcrumb m-0 py-0">
		<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
		<li class="breadcrumb-item"><a href="{{ route('admin.loan.pending') }}">{{ __('Pending Loans') }}</a></li>
	</ol>
	</div>
</div>


<div class="row mt-3">
  <div class="col-lg-12">

	@include('includes.admin.form-success')

	<div class="card mb-4">
	  <div class="table-responsive p-3">
		<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
		  <thead class="thead-light">
			<tr>
				<th>{{__('Plan No')}}</th>
				<th>{{__('Loan Amount')}}</th>
				<th>{{__('User')}}</th>
				<th>{{__('Total Installment')}}</th>
				<th>{{__('Total Amount')}}</th>
				<th>{{__('Next Installment')}}</th>
				<th>{{__('Average Amount')}}</th>
				<th>{{__('Message')}}</th>
				<th>{{__('Status')}}</th>
				<th>{{__('Action')}}</th>
			</tr>
		  </thead>
		</table>
	  </div>
	</div>
  </div>
</div>

{{-- STATUS MODAL --}}
<div class="modal fade confirm-modal" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalTitle" aria-hidden="true">
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

				<div class="form-group">
					<label for="Message">{{ __('Message') }} </label>
					<textarea name="statusMsg" class="form-control" id="statusMsg" placeholder = "Please write the message."required=""></textarea>
				</div>

				<p id="error-message" style="color:red;"> </p>

				<div class="form-group update">
	            	<label for="Update Amount">{{ __('Update Amount') }} </label>
	            	<input type="number" class="form-control" id="update_amount" name="update_amount" placeholder="{{ __('Update Amount') }}">
	          	</div>

	          	<div class="form-group update">
	            	<label for="Update Per Installment Amount">{{ __('Update Per Installment Amount') }} </label>
	            	<input type="number" class="form-control" id="update_installment_amount" name="update_installment_amount" placeholder="{{ __('Update  Per Installment Amount') }}">
	          	</div>

				<input type="hidden" id="statusId" name="statusId" value="">
				<input type="hidden" id="status" name="status" value="0">
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</a>
				<a href="javascript:;" class="btn btn-success" onclick="statusChange();">{{ __("Update") }}</a>
				
			</div>
		</div>
	</div>
</div>
{{-- STATUS MODAL ENDS --}}

<div class="modal fade confirm-modal" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="statusModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __("Transactions") }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="modal-body" id="modalContent">
			    </div>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</a>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')

<script type="text/javascript">
	"use strict";
	function getTransaction(identifier)
	{     
        var data_transactions = $(identifier).data('transactions');
    	$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{{URL::to('/admin/getTransactions')}}",
            data: {
            	'fileName':data_transactions},
            	success: function(response){
                	// alert(response);
                	$('#modalContent').html(response);
                	$('#transactionModal').modal('show');
            	}
        	});
    }
    

	$(document).ready(function(){

    // Event listener for status links
    	 });
    var table = $('#geniustable').DataTable({
           ordering: false,
           processing: true,
           serverSide: true,
           searching: false,
           ajax: '{{ route('admin.loan.datatables',['status'=> 0]) }}',
           columns: [
				{ data: 'transaction_no', name: 'transaction_no' },
				{ data: 'loan_amount', name: 'loan_amount' },
				{ data: 'user_id', name: 'user_id' },
				{ data: 'total_installment', name: 'total_installment' },
				{ data: 'total_amount', name: 'total_amount' },
				{ data: 'next_installment', name: 'next_installment' },
				{ data: 'average_amount', name: 'average_amount' },
				{ data: 'message', name: 'message' },
				{ data: 'status', name: 'status' },
				{ data: 'action', searchable: false, orderable: false }
            ],
            language : {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            }
        });

</script>

@endsection


