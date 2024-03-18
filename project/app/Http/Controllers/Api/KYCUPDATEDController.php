<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreKYCUPDATEDRequest;
use App\Http\Requests\UpdateKYCUPDATEDRequest;
use App\Models\KYCUPDATED;
use App\Models\UserKycDocument;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\BaseController as controller;

class KYCUPDATEDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $url = 'https://svcdemo.digitap.work/bank-data/institutions';
        $data = [
            'type' => 'Statement',
        ];

        $username = '48130178';
        $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
        $auth = 'Basic ' . base64_encode($username . ':' . $password);

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => $auth,
            ],
            'json' => $data

        ]);

        $institutionStatement = $response->getBody()->getContents();

        $url1 = 'https://svcdemo.digitap.work/bank-data/institutions';
        $data1 = [
            'type' => 'NetBanking',
        ];
        $response1 = $client->request('POST', $url1, [
            'headers' => [
                'Authorization' => $auth,
            ],
            'json' => $data1

        ]);
        $institutionNetbanking = $response1->getBody()->getContents();

        $data['institutionStatement'] = json_decode($institutionStatement);
        $data['institutionNetbanking'] = json_decode($institutionNetbanking);
        $data['user'] = Auth::user();

        return $this->success($data, '');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreKYCUPDATEDRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKYCUPDATEDRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KYCUPDATED  $kYCUPDATED
     * @return \Illuminate\Http\Response
     */
    public function show(KYCUPDATED $kYCUPDATED)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KYCUPDATED  $kYCUPDATED
     * @return \Illuminate\Http\Response
     */
    public function edit(KYCUPDATED $kYCUPDATED)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKYCUPDATEDRequest  $request
     * @param  \App\Models\KYCUPDATED  $kYCUPDATED
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKYCUPDATEDRequest $request, KYCUPDATED $kYCUPDATED)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KYCUPDATED  $kYCUPDATED
     * @return \Illuminate\Http\Response
     */
    public function destroy(KYCUPDATED $kYCUPDATED)
    {
        //
    }

    public function digilockerVerify()
    {
        $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $url = 'https://svcdemo.digitap.work/ent/v1/kyc/generate-url';
        $data = [
            'serviceId' => '4',
            'uid' => $random,
            'firstName' => $_POST['dfname'],
            'lastName' => $_POST['dlname'],
            'mobile' => $_POST['dmobile'],
            'emailId' => $_POST['demail']
        ];

        $username = '48130178';
        $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
        $auth = 'Basic ' . base64_encode($username . ':' . $password);

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => $auth,
            ],
            'json' => $data

        ]);

        $result = $response->getBody()->getContents();
        if (empty ($result)) {
            return \Response::json(['status' => 0, 'data' => []]);
        } else {
            //Insert data into table
            $user_id = \Auth::id();
            $document = UserKycDocument::where('user_id', $user_id)->first();
            // dd($document);
            if (!empty ($document)) {
                $document->api_response_digilocker = $result;
                $document->save();
            } else {
                $insertData = array();
                $insertData = array('user_id' => $user_id, 'api_response_digilocker' => $result);
                UserKycDocument::create($insertData);
            }

            //end insertion data

            return \Response::json(['status' => 1, 'data' => $result]);
        }
    }

    public function digilockerVerifyCheck5()
    {
        $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $url = 'https://svcdemo.digitap.work/ent/v1/kyc/get-digilocker-details';
        $data = [
            'transactionId' => $_POST['transactionId']
        ];

        $username = '48130178';
        $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
        $auth = 'Basic ' . base64_encode($username . ':' . $password);

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => $auth,
            ],
            'json' => $data

        ]);

        $result = $response->getBody()->getContents();
        if (empty ($result)) {
            return \Response::json(['status' => 0, 'data' => []]);
        } else {
            //Insert data into table
            $user_id = \Auth::id();
            $document = UserKycDocument::where('user_id', $user_id)->first();

            /*$userKyc_update = User::where('id', $user_id)->first();
            $userKyc_update->kyc_status = 1;
            $userKyc_update->kyc_info = 1;
            $userKyc_update->save();*/

            $document->api_response_digilocker_status = $result;
            $document->save();

            return \Response::json(['status' => 1, 'data' => $result]);
        }
    }

    public function documentVerify()
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        switch ($_POST['type']) {
            case ('aadhaar'):
                $url = 'https://svcdemo.digitap.work/validation/kyc/v1/aadhaar';
                $data = [
                    'aadhaar' => $_POST['document'],
                    'client_ref_num' => 'test',
                ];
                break;

            case ('pan'):
                $url = 'https://svcdemo.digitap.work/validation/kyc/v1/pan_details';
                $data = [
                    'pan' => $_POST['document'],
                    'client_ref_num' => 'test',
                ];
                break;

            default:
                $url = '';
                $data = [];
        }

        if ($url == '') {
            return \Response::json(['status' => 0, 'data' => []]);
        } else {
            $username = '48130178';
            $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
            $auth = 'Basic ' . base64_encode($username . ':' . $password);

            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => $auth,
                ],
                'json' => $data

            ]);

            $result = $response->getBody()->getContents();
            if (empty ($result)) {
                return \Response::json(['status' => 0, 'data' => []]);
            } else {
                //Insert data into table
                $user_id = \Auth::id();
                $document = UserKycDocument::where('user_id', $user_id)->first();
                // dd($document);
                if (!empty ($document)) {
                    if ($_POST['type'] == 'aadhaar') {
                        $document->api_response_aadhar = $result;
                    } elseif ($_POST['type'] == 'pan') {
                        $document->api_response_pan = $result;
                    }
                    $document->save();
                    /*$userKyc_update = User::where('id', $user_id)->first();
                    $userKyc_update->kyc_status = 1;
                    $userKyc_update->kyc_info = 1;
                    $userKyc_update->save();*/
                } else {
                    $insertData = array();
                    if ($_POST['type'] == 'aadhaar') {
                        $insertData = array('user_id' => $user_id, 'api_response_aadhar' => $result);
                    } elseif ($_POST['type'] == 'pan') {
                        $insertData = array('user_id' => $user_id, 'api_response_pan' => $result);
                    }
                    UserKycDocument::create($insertData);
                }

                //end insertion data

                return \Response::json(['status' => 1, 'data' => $result]);
            }
        }
    }


    public function uploadStatement()
    {
        $random = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);
        //print_r($_POST);
        switch ($_POST['type']) {
            case ('stetment'):
                $url = 'https://svcdemo.digitap.work/bank-data/generateurl';
                $data = [
                    'client_ref_num' => $random,
                    'txn_completed_cburl' => url('/user/kyc/kyc_verify_status/'),
                    'institution_id' => $_POST['institutionStatement'],
                    'destination' => 'statementupload',
                    'acceptance' => 'atleastOneTransactionInRange',
                    'return_url' => url('/user/kyc/kyc_verify_status/'),
                    /*'return_url' => url('/user/kyc/').'/?transID='.$random.'&status=cancle',*/
                ];
                break;

            case ('netBanking'):
                $transID = mt_rand(10000000, 99999999);
                $_SESSION['transID'] = $transID;
                $url = 'https://svcdemo.digitap.work/bank-data/generateurl';
                $data = [
                    'client_ref_num' => $random,
                    'txn_completed_cburl' => url('/user/kyc/kyc_verify_status/'),
                    'institution_id' => $_POST['institutionNetbanking'],
                    'destination' => 'netbanking',
                    'acceptance' => 'atleastOneTransactionInRange',
                    'return_url' => url('/user/kyc/kyc_verify_status/'),
                    /*'return_url' => url('/user/kyc/').'/?transID='.$random.'&status=cancle',*/
                ];
                break;

            default:
                $url = '';
                $data = [];
        }
        /*   print_r($data);
   die;*/

        if ($url == '') {
            return \Response::json(['status' => 0, 'data' => []]);
        } else {
            $username = '48130178';
            $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
            $auth = 'Basic ' . base64_encode($username . ':' . $password);

            //dd($url);
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => $auth,
                ],
                'json' => $data

            ]);

            $result = $response->getBody()->getContents();

            if (empty ($result)) {
                return \Response::json(['status' => 0, 'data' => []]);
            } else {
                //$result = json_decode($result);
                //dd($resultData->url);
                //Insert data into table
                /*return Redirect::to($resultData->url);*/
                $user_id = \Auth::id();
                $document = UserKycDocument::where('user_id', $user_id)->first();
                $document->api_response_banking = $result;
                $update = $document->save();

                return \Response::json(['status' => 1, 'data' => $result]);
            }
        }
    }

    public function step1_document(Request $request)
    {
        $user_id = \Auth::id();

        $aadharfront = $user_id . '_aadharfront_' . time() . '.' . $request->fId->extension();
        $type = $request->fId->getClientMimeType();
        $size = $request->fId->getSize();
        $aadharfrontImg = $request->fId->move(storage_path('kyc'), $aadharfront);

        $aadharback = $user_id . '_aadharback_' . time() . '.' . $request->bId->extension();
        $type = $request->bId->getClientMimeType();
        $size = $request->bId->getSize();
        $request->bId->move(storage_path('kyc'), $aadharback);

        $panfront = $user_id . '_panfront_' . time() . '.' . $request->fIdp->extension();
        $type = $request->fIdp->getClientMimeType();
        $size = $request->fIdp->getSize();
        $request->fIdp->move(storage_path('kyc'), $panfront);

        $panback = $user_id . '_panback_' . time() . '.' . $request->bIdp->extension();
        $type = $request->bIdp->getClientMimeType();
        $size = $request->bIdp->getSize();
        $request->bIdp->move(storage_path('kyc'), $panback);

        $document = UserKycDocument::where('user_id', $user_id)->first();

        $create = '';
        $update = '';
        if (!empty ($document) && !empty ($aadharfront) && !empty ($aadharback) && !empty ($panfront) && !empty ($panback)) {
            $current_address = $_POST['current_address'];
            $document->current_address = $current_address;
            $document->aadhaar_front = $aadharfront;
            $document->aadhaar_back = $aadharback;
            $document->pan_front = $panfront;
            $document->pan_back = $panback;
            $document->aadhaar_no = $_POST['aadhar_number'];
            $document->pan_no = $_POST['pan-number'];
            $update = $document->save();
        } else {
            $data = ['user_id' => $user_id, 'current_address' => $current_address];
            $create = UserKycDocument::create($data);
        }
        //$create = UserKycDocument::create($data);
        if ($create) {
            return \Response::json(['status' => 1, 'message' => "Data Added Successfully"]);
        } elseif ($update) {
            return \Response::json(['status' => 1, 'message' => "Data Updated Successfully"]);
        } else {
            return \Response::json(['status' => 0, 'message' => "something went wrong please try again later"]);
        }
    }

    public function kycVerifyStatus()
    {
        $user_id = \Auth::id();
        //$document = UserKycDocument::where('user_id', $user_id)->first();

        //dd($document);
        $kycInformations = UserKycDocument::where('user_id', $user_id)->first();
        $kycApiResponse = json_decode($kycInformations);
        $apiResponseBanking = json_decode($kycApiResponse->api_response_banking);
        //dd($apiResponseBanking);
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $url = 'https://svcdemo.digitap.work/bank-data/statuscheck';
        $data = [
            'request_id' => $apiResponseBanking->request_id,
        ];

        $username = '48130178';
        $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
        $auth = 'Basic ' . base64_encode($username . ':' . $password);

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => $auth,
            ],
            'json' => $data

        ]);

        $statusCheck = '';
        $statusCheckApiStatus = $response->getBody()->getContents();
        $statusCheckApiStatus = json_decode($statusCheckApiStatus);
        $statusCheck = $statusCheckApiStatus->txn_status[0]->status;
        //dd($statusCheckApiStatus);
        if ($statusCheck == 'Success') {
            $userKyc_update = User::where('id', $user_id)->first();
            $userKyc_update->kyc_status = 1;
            $userKyc_update->kyc_info = 1;
            $userKyc_update->save();
            return redirect('/user/dashboard?kyc=1');
        } else {
            return redirect('/user/dashboard?kyc=0');
        }
    }

    // FUNCTION WHEN GET REQUEST FOR VKYC API
    public function vkycVerify()
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $data = array();
        $user_id = \Auth::id();
        Log::info(base64_encode($user_id) . " ====> Call api for vkyc ");

        // GET APPROVE DETAIL FOR VKYC
        $document = UserKycDocument::where('user_id', $user_id)->first();

        // IF DOCUMENT EMPTY USER DETAIL NOT AVALABLE
        if (!empty ($document)) {
            $detail = json_decode($document->api_response_pan);
            if (!empty ($detail)) {

                $detail = $detail->result;
                $data = [
                    "fname" => $detail->first_name,
                    "applicationNumber" =>  $document->user_id,
                    "mobile" => $detail->mobile,
                    "email" => $detail->email,
                    "skipOkyc" => "TRUE",
                    "sendSms" => true,
                    "sendEmail" => true,
                    "redirectionUrl" => route('user.vkyc.update')
                ];

            }

            // API CALL PARAMS
            $url = env('KYC_API_URL') . "demo/v1/vkyc/okyc/user/activate";
            $username = env('KYC_CLIENT_ID');
            $password = env('KYC_CLIENT_PASSWORD');
            $auth = 'Basic ' . base64_encode($username . ':' . $password);

            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => $auth,
                    'ent_authorization' => env('KYC_CLIENT_TOKEN')
                ],
                'json' => $data

            ]);

            // GET RESULT FROM API
            $result = $response->getBody()->getContents();
            if (empty ($result)) {
                Log::error(base64_encode($user_id) . " ====> Call api for vkyc result is empty");

                return \Response::json(['status' => 0, 'data' => [], 'message' => "Something went wrong"]);
            } else {
                $data = json_decode($result);
                Log::info(base64_encode($user_id) . " ===> Call api for vkyc result" . $result);

                //    IF KYC DONE UPDATE IN TABLE 
                if ($data->model->vkycCompleted == "true" || $data->model->vkycCompleted || true) {
                    Log::info(json_encode($user_id) . " ===> Call api for vkyc if vkyc complete save result" . json_encode($data));
                    $this->vkycStatusByUniqueId();
                }
                Log::info(base64_encode($user_id) . " ===> Call api for vkyc response api and redirect" . json_encode($data));

                return \Response::json(['status' => 1, 'data' => $result, 'message' => '']);
            }
        } else {
            Log::info(base64_encode($user_id) . " ===> Call api for vkyc but kyc not verified");
            return \Response::json(['status' => 0, 'data' => [], 'message' => 'Kyc not verified']);
        }

    }


    // FUNCTION WHEN GET RESPONSE FROM VKYC API
    public function vkycUpdate(Request $request)
    {
        $user_id = \Auth::id();
        $data = json_encode($request->all());
        Log::info(base64_encode($user_id) . " ===> get response from vkyc callback" . $data);

        $kyc = 0;
        $userVKyc_update = UserKycDocument::where('user_id', $user_id)->first();
        $userVKyc_update->api_response_vkyc = $data;
        $userVKyc_update->save();
        if ($request->get('vkycstatus') == "true") {
            Log::info(base64_encode($user_id) . " ===> get response from vkyc callback and save vkyc approve" . $data);

            $userVKyc_user_update = User::where('id', $user_id)->first();
            $userVKyc_user_update->vkyc_status = 1;
            $userVKyc_user_update->save();
            $kyc = 1;
        }
        return redirect('/user/dashboard?kyc=1');

    }

    // FUNCTION WHEN GET REQUEST FOR VKYC API
    public function vkycStatusByUniqueId()
    {

        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $data = array();
        $user_id = \Auth::id();
        $transaction_id = $user_id;
        Log::info(base64_encode($user_id) . " ====> get vkyc data by transaction id ===> " . $transaction_id);

        // GET APPROVE DETAIL FOR VKYC
        $document = UserKycDocument::where('user_id', $user_id)->first();

        // IF DOCUMENT EMPTY USER DETAIL NOT AVALABLE
        if (!empty ($document)) {
            $detail = json_decode($document->api_response_pan);
            if (!empty ($detail)) {

                $detail = $detail->result;
                $data = [
                    "uniqueId" => $user_id
                ];

            }

            // API CALL PARAMS
            $url =  env('KYC_API_URL') . 'demo/v1/vkyc/additional-info/transaction/session-info-uniqueid';
            $username = env('KYC_CLIENT_ID');
            $password = env('KYC_CLIENT_PASSWORD');
            $auth = 'Basic ' . base64_encode($username . ':' . $password);

            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => $auth,
                    'ent_authorization' => env('KYC_CLIENT_TOKEN')
                ],
                'json' => $data

            ]);

            // GET RESULT FROM API
            $result = $response->getBody()->getContents();
            if (empty ($result)) {
                Log::error(base64_encode($user_id) . " ====> vkyc data data empty transaction id ===> " . $transaction_id);

                return \Response::json(['status' => 0, 'data' => [], 'message' => "Something went wrong"]);
            } else {
                $data = json_decode($result);
                Log::info(base64_encode($user_id) . " ===> Call api get data by transaction id ===>" . $transaction_id ." result =>" . $result);

                // SAVE RESPONSE OF VKYC VERIFIED
                $document->api_response_vkyc = $result;
                $document->save();

                //    IF KYC DONE UPDATE IN TABLE 
                if ($data->model->vkycStatus == "APPROVED") {
                    Log::info(base64_encode($user_id) . " ===> Call api for vkyc if vkyc complete save result" . json_encode($data));

                    $userVKyc_user_update = User::where('id', $user_id)->first();
                    $userVKyc_user_update->vkyc_status = 1;
                    $userVKyc_user_update->save();
                }
                Log::info(base64_encode($user_id) . " ===> Call api for vkyc response api and redirect" . json_encode($data));

                return \Response::json(['status' => 1, 'data' => $result, 'message' => '']);
            }
        } else {
            Log::info(base64_encode($user_id) . " ===> Call api for vkyc but kyc not verified");
            return \Response::json(['status' => 0, 'data' => [], 'message' => 'Kyc not verified']);
        }

    }
}
