<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\InstallmentLog;
use App\Models\User;
use App\Models\UserLoan;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Carbon;
use App\Models\LoanMessageHistory;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;


class LoanController extends Controller
{
    public function __construct()
    {
    }

    public function datatables(Request $request)
    {
        if ($request->status == 'all') {
            $datas = UserLoan::orderBy('id', 'desc')->get();
        } else {
            $datas = UserLoan::where('status', $request->status)->orderBy('id', 'desc')->get();
        }
        
         return Datatables::of($datas)
                            ->editColumn('transaction_no', function (UserLoan $data) {
                                return '<div>
                                      '.$data->transaction_no.'
                                      <br>
                                      <span class="text-info">'.$data->plan->title.'</span>
                              </div>';
                            })

                            ->editColumn('user_id', function (UserLoan $data) {
                                return '<div>
                                          <span>'.$data->user->name.'</span>
                                          <p>'.$data->user->account_number.'</p>
                                      </div>';
                            })

                            ->editColumn('loan_amount', function (UserLoan $data) {
                                return  '<div>
                                          '.showNameAmount($data->loan_amount).'
                                          <br>
                                          <span class="text-info">Per Installment '.showNameAmount($data->per_installment_amount).'</span>
                                      </div>';
                            })

                            ->editColumn('total_installment', function (UserLoan $data) {
                                return '<div>
                                      '.$data->total_installment.'
                                      <br>
                                      <span class="text-info">'.$data->given_installment.' Given</span>
                              </div>';
                            })

                            ->editColumn('total_amount', function (UserLoan $data) {
                                return  '<div>
                                          '.showNameAmount($data->total_installment * $data->per_installment_amount).'
                                          <br>
                                          <span class="text-info">Paid Amount '.showNameAmount($data->paid_amount).'</span>
                                      </div>';
                            })

                            ->editColumn('next_installment', function (UserLoan $data) {
                                return $data->next_installment ? $data->next_installment->toDateString() : '--';
                            })

                            ->editColumn('average_amount', function (UserLoan $data) {
                                if ($data->status==0 ) {
                                    if(Storage::exists($data->userKycDocument->bank_details_file_name)) {
                                      $amount = ($data->userKycDocument->bank_details_file_name != '' && isset($data->userKycDocument->bank_details_file_name)) ? averageAmount($data->userKycDocument->bank_details_file_name,'average') : NULL;

                                      if($amount != NULL) {
                                        return  '<div>
                                              '.$amount['averageAmount'].'
                                              <br>
                                               <a href="javascript:;" onclick="getTransaction(this);" data-transactions='.$data->userKycDocument->bank_details_file_name.'  style="text-decoration: none;"><span class="text-info">Transactions</span></a>
                                          </div>';
                                      } else {
                                        return '-';
                                      }
                                    }  else {
                                        return '-';
                                    }
                                  
                              } else {
                                 return '-';
                              }

                            })

                            ->addColumn('status', function (UserLoan $data) {

                                if ($data->status==0) {
                                    $status= __('Pending');
                                } elseif ($data->status==1) {
                                    $status= __('Running');
                                } elseif ($data->status==3) {
                                    $status=__('Completed');
                                } 
                                elseif ($data->status==4) {
                                    $status=__('Esign Pending');
                                }
                                else {
                                    $status=__('Rejected');
                                }

                                if ($data->status==1) {
                                    $status_sign='success';
                                } elseif ($data->status==0) {
                                    $status_sign='warning';
                                } elseif ($data->status==3) {
                                    $status_sign='info';
                                } else {
                                    $status_sign='danger';
                                }

                                if ($data->status==3) {
                                    return '<div class="btn-group mb-1">
                                    <span class="badge bg-'.$status_sign.'">'.$status .'</span>
                                </div>';
                                } else if ($data->status== 4) {
                                    if($data->esign_url) {
                                        return '<div class="btn-group mb-1">
                                        <a href='.$data->esign_url.' target="_blank" class="button"> Download Esign Doc </a>

                                        <a href="javascript:;" onclick="getStatusDataId(this);" data-id='.$data->id.' data-status="1"  class="dropdown-item" data-toggle="modal" data-target="#statusModal" class="dropdown-item">'.__("Approved").'</a>
                                        </div>';
                                    } else {
                                        return ' Esign Pending';
                                    }
                                } 
                                else {
                                    return '<div class="btn-group mb-1">
                                        <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          '.$status .'
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start">
                                          <a href="javascript:;" onclick="getStatusDataId(this);" data-id='.$data->id.' data-status="0" class="dropdown-item"  data-toggle="modal" data-target="#statusModal" class="dropdown-item">'.__("Pending").'</a>
                                           <a href="javascript:;" onclick="getStatusDataId(this);" data-id='.$data->id.' data-status="4" class="dropdown-item"  data-toggle="modal" data-target="#statusModal" class="dropdown-item">'.__("Esign").'</a>
                                          <a href="javascript:;" onclick="getStatusDataId(this);" data-id='.$data->id.' data-status="1"  class="dropdown-item" data-toggle="modal" data-target="#statusModal" class="dropdown-item">'.__("Approved").'</a>
                                          <a href="javascript:;" onclick="getStatusDataId(this);" data-id='.$data->id.' data-status="2"  class="dropdown-item" data-toggle="modal" data-target="#statusModal" class="dropdown-item">'.__("Rejected").'</a>
                                        </div>
                                      </div>';
                                }
                            })

                         ->addColumn('action', function (UserLoan $data) {

                            return '<div class="btn-group mb-1">
                          <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.'Actions' .'
                          </button>
                          <div class="dropdown-menu" x-placement="bottom-start">
                            <a href="' . route('admin.loan.log.show', $data->id) . '"  class="dropdown-item">'.__("Logs").'</a>
                            <a href="' . route('admin.loan.show', $data->id) . '"  class="dropdown-item">'.__("Details").'</a>
                          </div>
                        </div>';
                         })
                        ->rawColumns(['transaction_no','user_id','loan_amount','total_installment','total_amount','next_installment','status','average_amount','action'])
                        ->toJson();
    }

    public function index()
    {
        $this->installmentCheck();
        return view('admin.loan.index');
    }

    public function running()
    {
        $this->installmentCheck();
        return view('admin.loan.running');
    }

    public function completed()
    {
        return view('admin.loan.completed');
    }

    public function pending()
    {
        return view('admin.loan.pending');
    }

    public function rejected()
    {
        return view('admin.loan.rejected');
    }

    public function status($id1, $id2)
    {
        $data = UserLoan::findOrFail($id1);
        if ($data->status == 1) {
            $msg = 'Already Running this loan!';
            return response()->json($msg);
        }

        if ($id2 == 1) {
            if ($user = User::where('id', $data->user_id)->first()) {
                $user->balance += $data->loan_amount;
                $user->update();
            }
            $data->next_installment = Carbon::now()->addDays($data->plan->installment_interval);
        }
        $data->status = $id2;
        $data->update();
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function status1(Request $request)
    {
        $statusId = $request->statusId;
        $statusMsg = $request->statusMsg;
        $status = $request->status;
        $loggedInUser = auth()->id();

        $data = UserLoan::findOrFail($statusId);
        if ($data->status == 1) {
            $msg = 'Already Running this loan!';
            return response()->json($msg);
        }

        if ($status == 1) {
            if ($user = User::where('id', $data->user_id)->first()) {
                $user->balance += $data->loan_amount;
                $user->update();
            }
            $data->next_installment = Carbon::now()->addDays($data->plan->installment_interval);
        }

        if ($status == 0) {
            // if status is pending
            if($request->updateInstallmentAmount != 0 && $request->updateAmount != 0) {
                $data->old_loan_amount = $data->loan_amount ;
                $data->old_per_installment_amount = $data->per_installment_amount;
                $data->loan_amount = $request->updateAmount;
                $data->per_installment_amount = $request->updateInstallmentAmount;
            }
        }
        $data->status = $status;
        $data->message = $statusMsg;
        $data->update();

        // store message in loan message history table.
        $message = new LoanMessageHistory();
        $message['loan_id'] = $statusId;
        $message['user_id'] = "1";
        $message['message'] = $statusMsg;
        $message['role'] = 'admin';
        $message->save();

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function show($id)
    {
        $data = UserLoan::findOrFail($id);
        $data['requiredInformations'] = json_decode($data->required_information, true);
        $data['data'] = $data;
        $data['currency'] = Currency::whereIsDefault(1)->first();

        return view('admin.loan.show', $data);
    }

    public function logShow($id)
    {
        $loan = UserLoan::findOrfail($id);
        $logs = InstallmentLog::where('transaction_no', $loan->transaction_no)->latest()->paginate(20);
        $currency = Currency::whereIsDefault(1)->first();

        return view('admin.loan.log', compact('loan', 'logs', 'currency'));
    }

    public function installmentCheck()
    {
        $loans = UserLoan::whereStatus(1)->get();
        $now = Carbon::now();

        foreach ($loans as $key => $data) {
            if ($data->given_installment == $data->total_installment) {
                return false;
            }
            if ($now->gt($data->next_installment)) {
                $this->takeLoanAmount($data->user_id, $data->per_installment_amount);
                $this->logCreate($data->transaction_no, $data->per_installment_amount, $data->user_id);

                $data->next_installment = Carbon::now()->addDays($data->plan->installment_interval);
                $data->given_installment += 1;
                $data->paid_amount += $data->per_installment_amount;
                $data->update();

                if ($data->given_installment == $data->total_installment) {
                    $this->paid($data);
                }
            }
        }
    }

    public function takeLoanAmount($userId, $installment)
    {
        $user = User::whereId($userId)->first();
        if ($user && $user->balance>=$installment) {
            $user->balance -= $installment;
            $user->update();
        }
    }

    public function paid($loan)
    {
        $loan = UserLoan::whereId($loan->id)->first();
        if ($loan) {
            $loan->status = 3;
            $loan->next_installment = null;
            $loan->update();
        }
    }

    public function logCreate($transactionNo, $amount, $userId)
    {
        $data = new InstallmentLog();
        $data->user_id = $userId;
        $data->transaction_no = $transactionNo;
        $data->type = 'loan';
        $data->amount = $amount;
        $data->save();
    }

    public function getTransactions(Request $request)
    {
      $fileName = $request->fileName;
      // get transactions for this specific file name
      $transactions = averageAmount($fileName,'transaction');
      // Check if the array is not empty
      if (!empty($transactions)) {
        // Loop through each row of the array
        foreach ($transactions as $row) {
            echo '<div class="modal-row">';
            echo '<p>Narration: ' . $row['narration'] . '</p>';
            echo '<p>Date: ' . $row['date'] . '</p>';
            echo '<p>Balance: ' . $row['balance'] . '</p>';
            echo '<p>Amount: ' . $row['amount'] . '</p>';
            echo '<p>Cheque Number: ' . $row['cheque_num'] . '</p>';
            echo '</div>';
            echo '<hr>';
        }
      } else {
        echo '<p>No data available</p>';
      }

    } // END getTransactions

    public function esign() {
        $datas = UserLoan::orderBy('id', 'desc')->get();

        return view('admin.loan.esign', compact('datas'));

    }
}
