<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKYCUPDATEDRequest;
use App\Http\Requests\UpdateKYCUPDATEDRequest;
use App\Models\KYCUPDATED;
use App\Models\UserKycDocument;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\IpUtils;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\BaseController as controller;

class KYCUPDATEDController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kyc2()
    {

        $data = array();

        return view('user.kyc2', $data);
    }

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

        return view('user.kycupdated', $data);
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

    public function digilockerVerify(Request $request)
    {
        try {
            $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            //$url = 'https://api.digitap.ai/ent/v1/kyc/generate-url';
            $url = 'https://apidemo.digitap.work/ent/v1/kyc/generate-url';
            // $url = 'https://svcdemo.digitap.work/ent/v1/kyc/generate-url';

            $data = [
                'serviceId' => '4',
                'uid' => $random,
                'firstName' => $request->input('dfname'),
                'lastName' => $request->input('dlname'),
                'mobile' => $request->input('dmobile'),
                'emailId' => $request->input('demail')
            ];


            $username = '48130178';
            $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
            //$username = '34469987';
            //$password = 'RSzF0t3ZQNhqGqqtTyFrMmEMqxpTK728';

            $auth = base64_encode($username . ':' . $password);

            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => $auth,
                ],
                'json' => $data

            ]);

            $result = $response->getBody()->getContents();
            //dd($result);
            if (empty ($result)) {
                return $this->error([]);
                // return \Response::json(['status' => 0, 'data' => []]);
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
                return $this->success($result);
                // return \Response::json(['status' => 1, 'data' => $result]);
            }
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
        }
    }

    public function digilockerVerifyCheck(Request $request)
    {
        try {
            $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            $url = 'https://apidemo.digitap.work/ent/v1/kyc/get-digilocker-details';
            $data = [
                'transactionId' => $request->input('transactionId')
            ];

            $username = '48130178';
            $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
            $auth = base64_encode($username . ':' . $password);

            $response = $client->request('POST', $url, [
                'headers' => [
                    'ent_authorization' => $auth,
                ],
                'json' => $data

            ]);

            $result = $response->getBody()->getContents();
            //dd($result);
            if (empty ($result)) {
                return $this->error();
                // return \Response::json(['status' => 0, 'data' => []]);
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
                return $this->success($result);
                // return \Response::json(['status' => 1, 'data' => $result]);
            }
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
        }
    }

    public function documentVerify(Request $request)
    {
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            $type = $request->input('type');
            $document_r = $request->input('document');
            switch ($type) {
                case ('aadhaar'):
                    $url = 'https://svcdemo.digitap.work/validation/kyc/v1/aadhaar';
                    //$url = 'https://api.digitap.ai/validation/kyc/v1/aadhaar';
                    $data = [
                        'aadhaar' => $document_r,
                        'client_ref_num' => 'test',
                    ];
                    break;

                case ('pan'):
                    $url = 'https://svcdemo.digitap.work/validation/kyc/v1/pan_details';
                    $data = [
                        'pan' => $document_r,
                        'client_ref_num' => 'test',
                    ];
                    break;

                case ('voter'):
                    $url = 'https://svcdemo.digitap.work/validation/kyc/v1/voter';
                    $data = [
                        'epic_number' => $document_r,
                        'client_ref_num' => 'test',
                    ];
                    break;

                default:
                    $url = '';
                    $data = [];
            }

            if ($url == '') {
                return $this->error([]);
                // return \Response::json(['status' => 0, 'data' => []]);
            } else {
                $username = '48130178';
                $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';

                // $username = '34469987';
                // $password = 'RSzF0t3ZQNhqGqqtTyFrMmEMqxpTK728';
                $auth = 'Basic ' . base64_encode($username . ':' . $password);

                $response = $client->request('POST', $url, [
                    'headers' => [
                        'Authorization' => $auth,
                    ],
                    'json' => $data

                ]);

                $result = $response->getBody()->getContents();
                if (empty ($result)) {
                    return $this->error([]);
                    // return \Response::json(['status' => 0, 'data' => []]);
                } else {
                    //Insert data into table
                    $user_id = \Auth::id();
                    $document = UserKycDocument::where('user_id', $user_id)->first();
                    // dd($document);
                    if (!empty ($document)) {
                        if ($type == 'aadhaar') {
                            $document->api_response_aadhar = $result;
                        } elseif ($type == 'pan') {
                            $document->api_response_pan = $result;
                        }
                        $document->save();
                        /*$userKyc_update = User::where('id', $user_id)->first();
                        $userKyc_update->kyc_status = 1;
                        $userKyc_update->kyc_info = 1;
                        $userKyc_update->save();*/
                    } else {
                        $insertData = array();
                        if ($type == 'aadhaar') {
                            $insertData = array('user_id' => $user_id, 'api_response_aadhar' => $result);
                        } elseif ($_POST['type'] == 'pan') {
                            $insertData = array('user_id' => $user_id, 'api_response_pan' => $result);
                        }
                        UserKycDocument::create($insertData);
                    }

                    //end insertion data
                    return $this->success($result);
                }
            }
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
        }
    }


    public function uploadStatement(Request $request)
    {
        try {
            $random = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);
            //print_r($_POST);
            switch ($request->get('type')) {
                case ('stetment'):
                    $url = 'https://svcdemo.digitap.work/bank-data/generateurl';
                    $data = [
                        'client_ref_num' => $random,
                        'txn_completed_cburl' => url('/user/kyc/kyc_verify_status/'),
                        'institution_id' => $request->get('institutionStatement'),
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
                        'institution_id' => $request->get('institutionNetbanking'),
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
                return $this->error();
                // return \Response::json(['status' => 0, 'data' => []]);
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
                    return $this->error();
                    // return \Response::json(['status' => 0, 'data' => []]);
                } else {
                    //$result = json_decode($result);
                    //dd($resultData->url);
                    //Insert data into table
                    /*return Redirect::to($resultData->url);*/
                    $user_id = \Auth::id();
                    $document = UserKycDocument::where('user_id', $user_id)->first();
                    $document->api_response_banking = $result;
                    $update = $document->save();
                    return $this->success($result);
                    // return \Response::json(['status' => 1, 'data' => $result]);
                }
            }
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
        }
    }

    public function step1_document(Request $request)
    {
        try{
        $user_id = \Auth::id();
        $aadhar_no = "";
        $pan_no = "";
        $voter_no = "";
        $error = 0;
        $errorArray = array();
        $aadharfront = $user_id . '_aadharfront_' . time() . '.' . $request->fId->extension();
        $type = $request->fId->getClientMimeType();
        $size = $request->fId->getSize();
        $aadharfrontImg = $request->fId->move(storage_path('kyc'), $aadharfront);

        $imageContents = file_get_contents(storage_path("kyc/" . $aadharfront));
        // if (Storage::exists($filePath)) {
        //     dd("exists");
        // }
        $aadharFrontImage = imageVerification("aadhaar", $imageContents);
        //dd($aadharFrontImage);
        if ($aadharFrontImage['status'] == 'sucess') {
            $responseAadharFrontData = json_decode($aadharFrontImage['response'], true);

            if (isset ($responseAadharFrontData['status'])) {
                if ($responseAadharFrontData['status'] == 'failure') {
                    // dd($responseAadharFrontData["status"]);
                    $error = 1;
                    $errorArray[] = "Aadhar front end image is not valid. ";
                } elseif ($responseAadharFrontData['status'] == 'success') {
                    if (isset ($responseAadharFrontData['result'][0]['type'])) {
                        if ($responseAadharFrontData['result'][0]['type'] == "aadhaar_front_bottom") {
                            $aadhar_no = $responseAadharFrontData['result'][0]['details']['aadhaar']['value'];
                        }
                    }

                    if (isset ($responseAadharFrontData['result'][1]['type'])) {
                        if ($responseAadharFrontData['result'][1]['type'] == "aadhaar_front_bottom") {
                            $aadhar_no = $responseAadharFrontData['result'][1]['details']['aadhaar']['value'];
                        }
                    }

                    if ($aadhar_no == "") {
                        $error = 1;
                        $errorArray[] = "Aadhar front end image is not valid. ";
                    } elseif ($_POST['aadhar_number'] != $aadhar_no) {
                        $error = 1;
                        $errorArray[] = "Aadhar number does not match with aadhar card details.";
                    }
                }
            }
        } else {
            $error = 1;
            $errorArray[] = "Aadhar front end image is not valid.";
            // if (Storage::exists($filePath)) {
            //     Storage::delete($filePath);
            // }
        }
        $aadharback = $user_id . '_aadharback_' . time() . '.' . $request->bId->extension();
        $type = $request->bId->getClientMimeType();
        $size = $request->bId->getSize();
        $request->bId->move(storage_path('kyc'), $aadharback);

        $imageContents = file_get_contents(storage_path("kyc/" . $aadharback));
        $aadharBackImage = imageVerification("aadhaar", $imageContents);

        if ($aadharBackImage['status'] == 'sucess') {
            $responseAadhaBackData = json_decode($aadharBackImage['response'], true);

            if (isset ($responseAadhaBackData['status'])) {
                if ($responseAadhaBackData['status'] == 'failure') {
                    $errorArray[] = "Aadhar back image is not valid. ";
                } elseif ($responseAadhaBackData['status'] == 'success') {
                    $backend = 0;
                    if (isset ($responseAadhaBackData['result'][1])) {
                        if ($responseAadhaBackData['result'][1]['type'] == "aadhaar_back") {
                            $backend = 1;
                        }
                    }

                    if (isset ($responseAadhaBackData['result'][0])) {
                        if ($responseAadhaBackData['result'][0]['type'] == "aadhaar_back") {
                            $backend = 1;
                        }
                    }

                    if ($backend == 0) {
                        $errorArray[] = "Aadhar back image is not valid. ";
                    }
                }
            }
        } else {
            $error = 1;
            $errorArray[] = "Aadhar back image is not valid.";
            // if (Storage::exists($filePath)) {
            //     Storage::delete($filePath);
            // }
        }
        //dd($aadharBackImage);

        // print_r("aadhar_no ".$aadhar_no);
        // dd($request->all());
        $panfront = $user_id . '_panfront_' . time() . '.' . $request->fIdp->extension();
        $type = $request->fIdp->getClientMimeType();
        $size = $request->fIdp->getSize();
        $request->fIdp->move(storage_path('kyc'), $panfront);

        $imageContents = file_get_contents(storage_path("kyc/" . $panfront));
        $panfrontImage = imageVerification("pan", $imageContents);

        // dd($panfrontImage);
        if ($panfrontImage['status'] == 'sucess') {
            $responsePanData = json_decode($panfrontImage['response'], true);
            if (isset ($responsePanData['status'])) {
                if ($responsePanData['status'] == 'failure') {
                    // dd($responsePanData["status"]);
                    $error = 1;
                    $errorArray[] = "Pancard image is not valid. ";
                } elseif ($responsePanData['status'] == 'success') {
                    $pan_no = $responsePanData['result'][0]['details']['pan_no']['value'];
                    if ($pan_no == "") {
                        $error = 1;
                        $errorArray[] = "Pancard image is not valid";
                    } elseif ($_POST['pan-number'] != $pan_no) {
                        $error = 1;
                        $errorArray[] = "Pan number does not match with pancard details.";
                    }
                }
            }
        } else {
            $error = 1;
            $errorArray[] = "Pancard image is not valid.";
            // if (Storage::exists($filePath)) {
            //     Storage::delete($filePath);
            // }
        }
        // Voter image validation
        $voterfront = $user_id . '_voterfront_' . time() . '.' . $request->v_front->extension();
        $type = $request->v_front->getClientMimeType();
        $size = $request->v_front->getSize();
        $voterfrontImg = $request->v_front->move(storage_path('kyc'), $voterfront);

        $imageContents = file_get_contents(storage_path("kyc/" . $voterfront));
        // if (Storage::exists($filePath)) {
        //     dd("exists");
        // }
        $voterfrontImage = imageVerification("voter", $imageContents);

        if ($voterfrontImage['status'] == 'sucess') {
            $responseData = json_decode($voterfrontImage['response'], true);

            if (isset ($responseData['status'])) {
                if ($responseData['status'] == 'failure') {
                    // dd($responseData["status"]);
                    $error = 1;
                    $errorArray[] = "Voter front image is not valid. ";
                } elseif ($responseData['status'] == 'success') {
                    if (isset ($responseData['result'][0]['type'])) {
                        if ($responseData['result'][0]['type'] == "voterid_front_new") {
                            $voter_no = $responseData['result'][0]['details']['voterid']['value'];
                        }
                    }

                    if (isset ($responseData['result'][1]['type'])) {
                        if ($responseData['result'][1]['type'] == "voterid_front_new") {
                            $voter_no = $responseData['result'][1]['details']['voterid']['value'];
                        }
                    }

                    if ($voter_no == "") {
                        $error = 1;
                        $errorArray[] = "Voter front image is not valid. ";
                    } elseif ($_POST['voter_number'] != $voter_no) {
                        $error = 1;
                        $errorArray[] = "Voter number does not match with Voter card details.";
                    }
                }
            }
        } else {
            $error = 1;
            $errorArray[] = "Voter front image is not valid.";
            // if (Storage::exists($filePath)) {
            //     Storage::delete($filePath);
            // }
        }

        $voterback = $user_id . '_voterback_' . time() . '.' . $request->v_back->extension();
        $type = $request->v_back->getClientMimeType();
        $size = $request->v_back->getSize();
        $request->v_back->move(storage_path('kyc'), $voterback);

        $imageContents = file_get_contents(storage_path("kyc/" . $voterback));
        $voterBackImage = imageVerification("voter", $imageContents);

        if ($voterBackImage['status'] == 'sucess') {
            $responseData = json_decode($voterBackImage['response'], true);
            if (isset ($responseData['status'])) {
                if ($responseData['status'] == 'failure') {
                    $errorArray[] = "Voter back image is not valid. ";
                } elseif ($responseData['status'] == 'success') {
                    $backend = 0;
                    if (isset ($responseData['result'][1])) {
                        if ($responseData['result'][1]['type'] != "voterid_back_new") {
                            $backend = 1;
                        }
                    }

                    if (isset ($responseData['result'][0])) {
                        if ($responseData['result'][0]['type'] != "voterid_back_new") {
                            $backend = 1;
                        }
                    }

                    if ($backend == 1) {
                        $errorArray[] = "Voter back image is not valid. ";
                    }
                }
            }
        } else {
            $error = 1;
            $errorArray[] = "Voter back image is not valid.";
            // if (Storage::exists($filePath)) {
            //     Storage::delete($filePath);
            // }
        }



        if ($error == 1) {
            // Generate ascending numbers
            $ascendingNumbers = range(1, count($errorArray));

            // Combine numbers with values
            $combined = array_map(function ($number, $value) {
                return $number . ". " . $value;
            }, $ascendingNumbers, $errorArray);

            // Implode the combined array
            $errorMessage = implode(", ", $combined);
            return $this->error($errorArray, "something went wrong please try again later");
            // return \Response::json(['status' => 0, 'message' => "something went wrong please try again later", 'error' => $errorMessage]);
        }

        // $panback = $user_id . '_panback_' . time().'.'. $request->bIdp->extension();
        // $type = $request->bIdp->getClientMimeType();
        // $size = $request->bIdp->getSize();
        // $request->bIdp->move(storage_path('kyc'), $panback);

        $document = UserKycDocument::where('user_id', $user_id)->first();

        $create = '';
        $update = '';
        //
        if (!empty ($document) && !empty ($aadharfront) && !empty ($aadharback) && !empty ($panfront)) {
            //$current_address = $_POST['current_address'];
            //$document->current_address = $current_address;
            $document->aadhaar_front = $aadharfront;
            $document->aadhaar_back = $aadharback;
            $document->pan_front = $panfront;
            $document->voter_no = $_POST['voter_number'];
            $document->aadhaar_no = $_POST['aadhar_number'];
            $document->pan_no = $_POST['pan-number'];
            $document->api_response_aadhar_front_img = $aadharFrontImage['response'];
            $document->api_response_aadhar_back_img = $aadharBackImage['response'];
            $document->api_response_pan_front_img = $panfrontImage['response'];
            $document->api_response_voter_front_img = $voterfrontImage['response'];
            $document->api_response_voter_back_img = $voterBackImage['response'];
            $update = $document->save();
        } else {
            $data = ['user_id' => $user_id, 'aadhaar_front' => $aadharfront, 'pan_front' => $panfront, 'voter_no' => $_POST['voter_number'], 'aadhaar_no' => $_POST['aadhar_number'], 'pan_no' => $_POST['pan-number'], 'api_response_aadhar_front_img' => $aadharFrontImage['response'], 'api_response_aadhar_back_img' => $aadharBackImage['response'], 'api_response_pan_front_img' => $panfrontImage['response'], 'api_response_voter_front_img' => $voterfrontImage['response'], 'api_response_voter_back_img' => $voterBackImage['response'],];
            $create = UserKycDocument::create($data);
        }
        //$create = UserKycDocument::create($data);
        if ($create) {
            return $this->success([], "Data Added Successfully");
            // return \Response::json(['status' => 1, 'message' => "Data Added Successfully"]);
        } elseif ($update) {
            return $this->success([], "Data Updated Successfully");
            // return \Response::json(['status' => 1, 'message' => "Data Updated Successfully"]);
        } else {
            return $this->error([], "something went wrong please try again later");

            // return \Response::json(['status' => 0, 'message' => "something went wrong please try again later", 'error' => "something went wrong please try again later."]);
        }
    } catch (\Throwable $e) {
        return $this->error([], "Server error");
    }
    }

    public function step2_document(Request $request)
    {
        try{
        $user_id = \Auth::id();
        $document = UserKycDocument::where('user_id', $user_id)->first();
        if ($document->api_response_banking_details == null) {
            // return redirect('/user/dashboard');
            return $this->error([], "Details not found.");
        }
        $statusCheck = '0';
        $errorArray = array();
        $addressproof = $user_id . '_addressproof_' . time() . '.' . $request->fId->extension();
        $type = $request->fId->getClientMimeType();
        $size = $request->fId->getSize();
        $addressproofImg = $request->fId->move(storage_path('kyc'), $addressproof);

        /*$random= substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $url = 'https://api.digitap.ai/ent/v1/address-verification';
        $data = [
            'uniqueId' => $random,
            'latitude' => $_POST['latitude'],
            'longitude' => $_POST['longitude']
        ];

        $username = '34469987';
        $password = 'RSzF0t3ZQNhqGqqtTyFrMmEMqxpTK728';
        $auth = base64_encode($username . ':' . $password);

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => $auth,
            ],
            'json' => $data

        ]);

        $result = $response->getBody()->getContents();
        $statusCheckApiStatus = json_decode($result);
        $statusCheck = $statusCheckApiStatus->code;*/

        $document = UserKycDocument::where('user_id', $user_id)->first();
        //dd($document->api_response_digilocker_status);
        $update = '';
        //if ($statusCheck == '200') {
        if (!empty ($document) && !empty ($addressproof)) {
            if ($document->api_response_digilocker_status != null) {
                $api_response_digilocker_status = json_decode($document->api_response_digilocker_status);
                //dd($api_response_digilocker_status);
                if ($api_response_digilocker_status->model->address->dist != $_POST['district']) {
                    return $this->error([], "District not matched.");
                    // return \Response::json(['status' => 0, 'message' => "District not matched."]);
                }
                if ($api_response_digilocker_status->model->address->vtc != $_POST['city']) {
                    return $this->error([], "City not matched.");
                    // return \Response::json(['status' => 0, 'message' => "City not matched."]);
                }
                if ($api_response_digilocker_status->model->address->state != $_POST['state']) {
                    return $this->error([], "State not matched.");
                    // return \Response::json(['status' => 0, 'message' => "State not matched."]);
                }
                if ($api_response_digilocker_status->model->address->pc != $_POST['pincode']) {
                    return $this->error([], "Pincode not matched.");
                    // return \Response::json(['status' => 0, 'message' => "Pincode not matched."]);
                }
                if ($api_response_digilocker_status->model->address->country != $_POST['country']) {
                    return $this->error([], "Country not matched.");
                    // return \Response::json(['status' => 0, 'message' => "Country not matched."]);
                }
            } elseif ($document->api_response_aadhar_front_img != null && $document->api_response_aadhar_back_img != null) {
                $api_response_aadhar_back_img = json_decode($document->api_response_aadhar_back_img);
                /*print_r($api_response_aadhar_back_img->result[0]->details->address->house_number);
                die;*/
                $address = "";
                if (isset ($api_response_aadhar_back_img->result[1])) {
                    if ($api_response_aadhar_back_img->result[1]->type == "aadhaar_back") {
                        $address = $api_response_aadhar_back_img->result[1]->details->address;
                    }
                }

                if (isset ($api_response_aadhar_back_img->result[0])) {
                    if ($api_response_aadhar_back_img->result[0]->type == "aadhaar_back") {
                        $address = $api_response_aadhar_back_img->result[0]->details->address;
                    }
                }

                if ($address->district != $_POST['district']) {
                    return $this->error([], "District not matched.");

                    // return \Response::json(['status' => 0, 'message' => "District not matched."]);
                }
                if ($address->city != $_POST['city']) {
                    return $this->error([], "City not matched.");

                    // return \Response::json(['status' => 0, 'message' => "City not matched."]);
                }
                if ($address->state != $_POST['state']) {
                    return $this->error([], "State not matched.");

                    // return \Response::json(['status' => 0, 'message' => "State not matched."]);
                }
                if ($address->pin != $_POST['pincode']) {
                    return $this->error([], "Pincode not matched.");

                    // return \Response::json(['status' => 0, 'message' => "Pincode not matched."]);
                }
            } else {
                return $this->error([], "Details not found.");
                // return \Response::json(['status' => 2, 'message' => "Details not found."]);
            }

            //$document->api_response_address = $result;
            $document->addressproof = $addressproof;
            $document->current_address = $_POST['house_no'] . ', ' . $_POST['street'] . ', ' . $_POST['landmark'] . ', ' . $_POST['district'] . ', ' . $_POST['pincode'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'];
            $document->office_current_address = $_POST['house_no1'] . ', ' . $_POST['street1'] . ', ' . $_POST['landmark1'] . ', ' . $_POST['district1'] . ', ' . $_POST['pincode1'] . ', ' . $_POST['city1'] . ', ' . $_POST['state1'] . ', ' . $_POST['country1'];
            $update = $document->save();
        }
        //echo '1';
        $userKyc_update = User::where('id', $user_id)->first();
        $userKyc_update->kyc_status = 1;
        $userKyc_update->kyc_info = 1;
        $userKyc_update->save();
        return $this->success([], "Kyc updated successfully.");
        // return \Response::json(['status' => 1, 'message' => "Kyc updated successfully."]);
        //return redirect('/user/dashboard?kyc=1');
        //return redirect('/user/kyc2');
        /*} else {
            return \Response::json(['status' => 2, 'message' => "Details not found."]);

            return redirect('/user/dashboard?kyc=0');
        }*/
    } catch (\Throwable $e) {
        return $this->error([], "Server error");
    }
    }

    public function kycVerifyStatus()
    {
        $user_id = \Auth::id();
        $kycInformations = UserKycDocument::where('user_id', $user_id)->first();
        $kycApiResponse = json_decode($kycInformations);
        $apiResponseBanking = json_decode($kycApiResponse->api_response_banking);
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

        $document = UserKycDocument::where('user_id', $user_id)->first();
        $document->api_response_banking_status = $statusCheckApiStatus;
        $document->save();
        $statusCheckApiStatus = json_decode($statusCheckApiStatus);
        $statusCheck = $statusCheckApiStatus->txn_status[0]->status;
        if ($statusCheck == 'Success') {
            $url = 'https://svcdemo.digitap.work/bank-data/retrievereport';
            $data = [
                'txn_id' => $statusCheckApiStatus->txn_status[0]->txn_id,
                'report_type' => "json",
                'report_subtype' => "type1"
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
            //dd($result);
            $filaName = $user_id . '_bank_details_' . time() . '_file.txt';
            Storage::disk('local')->put($filaName, $result);
            $result = json_decode($result);
            $document = UserKycDocument::where('user_id', $user_id)->first();

            if ($document->api_response_digilocker_status != null) {
                $api_response_digilocker_status = json_decode($document->api_response_digilocker_status);

                if (str_contains($result->customer_info->name, $api_response_digilocker_status->model->name)) {
                    return \Response::json(['status' => 0, 'message' => "Name is not matched."]);
                }
            } elseif ($document->api_response_aadhar_front_img != null && $document->api_response_aadhar_back_img != null) {
                $api_response_aadhar_front_img = json_decode($document->api_response_aadhar_front_img);

                if (str_contains($result->customer_info->name, $api_response_aadhar_front_img->result[0]->details->name->value, )) {
                    return \Response::json(['status' => 0, 'message' => "Name is not matched."]);
                }
            } else {
                return \Response::json(['status' => 2, 'message' => "Details not found."]);
            }

            unset($result->accounts->transactions);

            $document->api_response_banking_details = json_encode($result);
            $document->bank_details_file_name = $filaName;
            $document->save();

            $document = UserKycDocument::where('user_id', $user_id)->first();

            return redirect('/user/kyc2');
        } else {
            return redirect('/user/dashboard?kyc=0');
        }
    }

    function getAddressLatLong(Request $request)
    {
        try {
            $user_id = \Auth::id();
            $document = UserKycDocument::where('user_id', $user_id)->first();
            if ($document->api_response_banking_details == null) {
                return redirect('/user/dashboard');
            }

            $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            $url = 'https://api.digitap.ai/ent/v1/address-verification';
            $data = [
                'uniqueId' => $random,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude')
            ];

            $username = '34469987';
            $password = 'RSzF0t3ZQNhqGqqtTyFrMmEMqxpTK728';
            $auth = base64_encode($username . ':' . $password);

            $response = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => $auth,
                ],
                'json' => $data

            ]);

            $result = $response->getBody()->getContents();
            $document = UserKycDocument::where('user_id', $user_id)->first();
            $document->api_response_address = $result;
            $document->save();
            return $result;
            //return '';
            /*if ($result) {
                return \Response::json(['status' => 1, 'message' => "", 'response' => $result]);
            } else {
                return \Response::json(['status' => 0, 'message' => ""]);
            }*/
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
        }
    }
    // FUNCTION WHEN GET REQUEST FOR VKYC API
    public function vkycVerify()
    {

        try {
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
                        "applicationNumber" => $document->user_id,
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
                    return $this->error([], "Something went wrong");
                    // return \Response::json(['status' => 0, 'data' => [], 'message' => "Something went wrong"]);
                } else {
                    $data = json_decode($result);
                    Log::info(base64_encode($user_id) . " ===> Call api for vkyc result" . $result);

                    //    IF KYC DONE UPDATE IN TABLE 
                    if ($data->model->vkycCompleted == "true" || $data->model->vkycCompleted || true) {
                        Log::info(json_encode($user_id) . " ===> Call api for vkyc if vkyc complete save result" . json_encode($data));
                        $this->vkycStatusByUniqueId();
                    }
                    Log::info(base64_encode($user_id) . " ===> Call api for vkyc response api and redirect" . json_encode($data));
                    return $this->success($result);
                    // return \Response::json(['status' => 1, 'data' => $result, 'message' => '']);
                }
            } else {
                Log::info(base64_encode($user_id) . " ===> Call api for vkyc but kyc not verified");
                return $this->success([], 'Kyc not verified');

                // return \Response::json(['status' => 0, 'data' => [], 'message' => 'Kyc not verified']);
            }
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
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
         try{
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
            $url = env('KYC_API_URL') . 'demo/v1/vkyc/additional-info/transaction/session-info-uniqueid';
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
                return $this->error([], "Something went wrong");

                // return \Response::json(['status' => 0, 'data' => [], 'message' => "Something went wrong"]);
            } else {
                $data = json_decode($result);
                Log::info(base64_encode($user_id) . " ===> Call api get data by transaction id ===>" . $transaction_id . " result =>" . $result);

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
                $this->success($result);
                // return \Response::json(['status' => 1, 'data' => $result, 'message' => '']);
            }
        } else {
            Log::info(base64_encode($user_id) . " ===> Call api for vkyc but kyc not verified");
            $this->error([],'Kyc not verified');
            // return \Response::json(['status' => 0, 'data' => [], 'message' => 'Kyc not verified']);
        }
    } catch (\Throwable $e) {
        return $this->error([], "Server error");
    }
    }
}
