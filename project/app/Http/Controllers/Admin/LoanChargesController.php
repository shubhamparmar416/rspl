<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanCharges;
use App\Models\LoanPlan;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; 


use function GuzzleHttp\json_decode;

class LoanChargesController extends Controller
{
	public function datatables()
    {
        $datas = LoanCharges::where('status', '1')->get();

        return Datatables::of($datas)
                            ->addColumn('action', function (LoanCharges $data) {

                                return '<div class="btn-group mb-1">
                                  <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    '.'Actions' .'
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start">
                                    <a href="' . route('admin.loan.charges.edit', $data->id) . '"  class="dropdown-item">'.__("Edit").'</a>
                                    <!--<a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="'.  route('admin.loan.plan.delete', $data->id).'">'.__("Delete").'</a>--!>
                                  </div>
                                </div>';
                            })
                            ->rawColumns(['name','gst_applicable','gst_percentage','amt_type','amt_value','action'])
                            ->toJson();
    }


    public function index()
    {
        return view('admin.loanCharges.index');
    }

    public function create()
    {

        return view('admin.loanCharges.create');
    }


    public function edit(Request $request, $id)
    {
        $data['data'] = LoanCharges::findOrFail($id);
        return view('admin.loanCharges.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = LoanCharges::findOrFail($id);
        $input = $request->all();

        if (!isset($request->amt_type)) {
            $input['amt_type'] = null;
        } 

        if (!isset($request->amt_value)) {
            $input['amt_value'] = null;
        } 

        $data->update($input);

        Log::info(" Loan Charges updated - ",['request_data' => $request->all()]);

        $msg = 'Loan Charges Updated Successfully.<a href="'.route('admin.loan.charges.index').'">View Loan Charges.</a>';
        return response()->json($msg);
    }

    public function store(Request $request)
    {
        $rules = [
          'name'=>'required|max:255',
          'status'=>'required|numeric|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $input = $request->all();

        $data = new LoanCharges();

        $data->fill($input)->save();

        Log::info("New Loan Charges added - ",['request_data' => $request->all()]);


        $msg = 'Loan Charges Added Successfully.<a href="'.route('admin.loan.charges.index').'">View Loan Charges.</a>';
        return response()->json($msg);
    }

}