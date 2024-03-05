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

class KYCUPDATEDController extends Controller
{
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

    public function digilockerVerify()
    {
        $random= substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        //$url = 'https://api.digitap.ai/ent/v1/kyc/generate-url';
        $url = 'https://apidemo.digitap.work/ent/v1/kyc/generate-url';
        // $url = 'https://svcdemo.digitap.work/ent/v1/kyc/generate-url';

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
        if (empty($result)) {
            return \Response::json(['status' => 0, 'data' => []]);
        } else {
                //Insert data into table
            $user_id = \Auth::id();
            $document = UserKycDocument::where('user_id', $user_id)->first();
           // dd($document);
            if (!empty($document)) {
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

    public function digilockerVerifyCheck()
    {
        $random= substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $client = new \GuzzleHttp\Client([
            'verify' => false
        ]);

        $url = 'https://apidemo.digitap.work/ent/v1/kyc/get-digilocker-details';
        $data = [
            'transactionId' => $_POST['transactionId']
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
        if (empty($result)) {
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
                //$url = 'https://api.digitap.ai/validation/kyc/v1/aadhaar';
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

            case ('voter'):
                $url = 'https://svcdemo.digitap.work/validation/kyc/v1/voter';
                $data = [
                    'epic_number' => $_POST['document'],
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
            if (empty($result)) {
                return \Response::json(['status' => 0, 'data' => []]);
            } else {
                    //Insert data into table
                $user_id = \Auth::id();
                $document = UserKycDocument::where('user_id', $user_id)->first();
               // dd($document);
                if (!empty($document)) {
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
        $random= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
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

            if (empty($result)) {
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
        $aadhar_no = "";
        $pan_no = "";
        $voter_no = "";
        $error = 0;
        $errorArray = array();
        $aadharfront = $user_id . '_aadharfront_' . time().'.'. $request->fId->extension();
        $type = $request->fId->getClientMimeType();
        $size = $request->fId->getSize();
        $aadharfrontImg = $request->fId->move(storage_path('kyc'), $aadharfront);
      
        $imageContents = file_get_contents(storage_path("kyc/".$aadharfront));
        // if (Storage::exists($filePath)) {
        //     dd("exists");
        // }
        $aadharFrontImage = imageVerification("aadhaar", $imageContents);
        //dd($aadharFrontImage);
        if ($aadharFrontImage['status'] == 'sucess') {
            $responseAadharFrontData = json_decode($aadharFrontImage['response'], true);

            if (isset($responseAadharFrontData['status'])) {
                if ($responseAadharFrontData['status'] == 'failure') {
                    // dd($responseAadharFrontData["status"]);
                    $error = 1;
                    $errorArray[] = "Aadhar front end image is not valid. ";
                } elseif ($responseAadharFrontData['status'] == 'success') {
                    if (isset($responseAadharFrontData['result'][0]['type'])) {
                        if ($responseAadharFrontData['result'][0]['type'] == "aadhaar_front_bottom") {
                            $aadhar_no = $responseAadharFrontData['result'][0]['details']['aadhaar']['value'];
                        }
                    }

                    if (isset($responseAadharFrontData['result'][1]['type'])) {
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

        $aadharback = $user_id . '_aadharback_' . time().'.'. $request->bId->extension();
        $type = $request->bId->getClientMimeType();
        $size = $request->bId->getSize();
        $request->bId->move(storage_path('kyc'), $aadharback);

        $imageContents = file_get_contents(storage_path("kyc/".$aadharback));
        $aadharBackImage = imageVerification("aadhaar", $imageContents);

        if ($aadharBackImage['status'] == 'sucess') {
            $responseAadhaBackData = json_decode($aadharBackImage['response'], true);
            if (isset($responseAadhaBackData['status'])) {
                if ($responseAadhaBackData['status'] == 'failure') {
                    $errorArray[] = "Aadhar back image is not valid. ";
                } elseif ($responseAadhaBackData['status'] == 'success') {
                    $backend = 0;
                    if (isset($responseAadhaBackData['result'][1])) {
                        if ($responseAadhaBackData['result'][1]['type'] != "aadhaar_back") {
                            $backend = 1;
                        }
                    }

                    if (isset($responseAadhaBackData['result'][0])) {
                        if ($responseAadhaBackData['result'][0]['type'] != "aadhaar_back") {
                            $backend = 1;
                        }
                    }

                    if ($backend == 1) {
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

        // print_r($errorArray);
        // print_r("aadhar_no ".$aadhar_no);
        // dd($request->all());
        $panfront = $user_id . '_panfront_' . time().'.'. $request->fIdp->extension();
        $type = $request->fIdp->getClientMimeType();
        $size = $request->fIdp->getSize();
        $request->fIdp->move(storage_path('kyc'), $panfront);

        $imageContents = file_get_contents(storage_path("kyc/".$panfront));
        $panfrontImage = imageVerification("pan", $imageContents);

        // dd($panfrontImage);
        if ($panfrontImage['status'] == 'sucess') {
            $responsePanData = json_decode($panfrontImage['response'], true);
            if (isset($responsePanData['status'])) {
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
        $voterfront = $user_id . '_voterfront_' . time().'.'. $request->v_front->extension();
        $type = $request->v_front->getClientMimeType();
        $size = $request->v_front->getSize();
        $voterfrontImg = $request->v_front->move(storage_path('kyc'), $voterfront);
      
        $imageContents = file_get_contents(storage_path("kyc/".$voterfront));
        // if (Storage::exists($filePath)) {
        //     dd("exists");
        // }
        $voterfrontImage = imageVerification("voter", $imageContents);

        if ($voterfrontImage['status'] == 'sucess') {
            $responseData = json_decode($voterfrontImage['response'], true);
            if (isset($responseData['status'])) {
                if ($responseData['status'] == 'failure') {
                    // dd($responseData["status"]);
                    $error = 1;
                    $errorArray[] = "Voter front image is not valid. ";
                } elseif ($responseData['status'] == 'success') {
                    if (isset($responseData['result'][0]['type'])) {
                        if ($responseData['result'][0]['type'] == "voterid_front_new") {
                            $voter_no = $responseData['result'][0]['details']['voterid']['value'];
                        }
                    }

                    if (isset($responseData['result'][1]['type'])) {
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

        $voterback = $user_id . '_voterback_' . time().'.'. $request->v_back->extension();
        $type = $request->v_back->getClientMimeType();
        $size = $request->v_back->getSize();
        $request->v_back->move(storage_path('kyc'), $voterback);

        $imageContents = file_get_contents(storage_path("kyc/".$voterback));
        $voterBackImage = imageVerification("voter", $imageContents);

        if ($voterBackImage['status'] == 'sucess') {
            $responseData = json_decode($voterBackImage['response'], true);
            if (isset($responseData['status'])) {
                if ($responseData['status'] == 'failure') {
                    $errorArray[] = "Voter back image is not valid. ";
                } elseif ($responseData['status'] == 'success') {
                    $backend = 0;
                    if (isset($responseData['result'][1])) {
                        if ($responseData['result'][1]['type'] != "voterid_back_new") {
                            $backend = 1;
                        }
                    }

                    if (isset($responseData['result'][0])) {
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



        if ($error==1) {
            // Generate ascending numbers
            $ascendingNumbers = range(1, count($errorArray));

            // Combine numbers with values
            $combined = array_map(function ($number, $value) {
                return $number . ". " . $value;
            }, $ascendingNumbers, $errorArray);

            // Implode the combined array
            $errorMessage = implode(", ", $combined);

            return \Response::json(['status' => 0, 'message' => "something went wrong please try again later", 'error' => $errorMessage]);
        }

        // $panback = $user_id . '_panback_' . time().'.'. $request->bIdp->extension();
        // $type = $request->bIdp->getClientMimeType();
        // $size = $request->bIdp->getSize();
        // $request->bIdp->move(storage_path('kyc'), $panback);
      
        $document = UserKycDocument::where('user_id', $user_id)->first();

        $create='';
        $update='';
        //
        if (!empty($document) && !empty($aadharfront) && !empty($aadharback) && !empty($panfront)) {
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
            $data = ['user_id' => $user_id,'aadhaar_front' => $aadharfront,'pan_front' => $panfront,'voter_no' => $_POST['voter_number'],'aadhaar_no' => $_POST['aadhar_number'],'pan_no' => $_POST['pan-number'],'api_response_aadhar_front_img' => $aadharFrontImage['response'],'api_response_aadhar_back_img' => $aadharBackImage['response'],'api_response_pan_front_img' => $panfrontImage['response'],'api_response_voter_front_img' => $voterfrontImage['response'],'api_response_voter_back_img' => $voterBackImage['response'],];
            $create = UserKycDocument::create($data);
        }
        //$create = UserKycDocument::create($data);
        if ($create) {
            return \Response::json(['status' => 1, 'message' => "Data Added Successfully"]);
        } elseif ($update) {
            return \Response::json(['status' => 1, 'message' => "Data Updated Successfully"]);
        } else {
            return \Response::json(['status' => 0, 'message' => "something went wrong please try again later", 'error' => "something went wrong please try again later."]);
        }
    }

    public function step2_document(Request $request)
    {
        $user_id = \Auth::id();
        $document = UserKycDocument::where('user_id', $user_id)->first();
        if ($document->api_response_banking_details == null) {
            return redirect('/user/dashboard');
        }
        $statusCheck = '0';
        $errorArray = array();
        $addressproof = $user_id . '_addressproof_' . time().'.'. $request->fId->extension();
        $type = $request->fId->getClientMimeType();
        $size = $request->fId->getSize();
        $addressproofImg = $request->fId->move(storage_path('kyc'), $addressproof);

        $random= substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
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
        $statusCheck = $statusCheckApiStatus->code;
      
        $document = UserKycDocument::where('user_id', $user_id)->first();

        $update='';
        
        if ($statusCheck == '200') {
            if (!empty($document) && !empty($addressproof)) {
                if ($document->api_response_digilocker_status != null) {
                    $api_response_digilocker_status = json_decode($document->api_response_digilocker_status);
                    if ($api_response_digilocker_status->model->address->dist != $_POST['district']) {
                        return \Response::json(['status' => 0, 'message' => "District not matched."]);
                    } elseif ($api_response_digilocker_status->model->address->vtc != $_POST['city']) {
                        return \Response::json(['status' => 0, 'message' => "City not matched."]);
                    } elseif ($api_response_digilocker_status->model->address->state != $_POST['state']) {
                        return \Response::json(['status' => 0, 'message' => "State not matched."]);
                    } elseif ($api_response_digilocker_status->model->address->pc != $_POST['pincode']) {
                        return \Response::json(['status' => 0, 'message' => "Pincode not matched."]);
                    } elseif ($api_response_digilocker_status->model->address->country != $_POST['country']) {
                        return \Response::json(['status' => 0, 'message' => "Country not matched."]);
                    }
                } elseif ($document->api_response_aadhar_front_img != null && $document->api_response_aadhar_back_img != null) {
                    $api_response_aadhar_back_img = json_decode($document->api_response_aadhar_back_img);
                    /*print_r($api_response_aadhar_back_img->result[0]->details->address->house_number);
                    die;*/
                    if ($api_response_aadhar_back_img->result[0]->details->address->district != $_POST['district']) {
                        return \Response::json(['status' => 0, 'message' => "District not matched."]);
                    } elseif ($api_response_aadhar_back_img->result[0]->details->address->city != $_POST['city']) {
                        return \Response::json(['status' => 0, 'message' => "City not matched."]);
                    } elseif ($api_response_aadhar_back_img->result[0]->details->address->state != $_POST['state']) {
                        return \Response::json(['status' => 0, 'message' => "State not matched."]);
                    } elseif ($api_response_aadhar_back_img->result[0]->details->address->pin != $_POST['pincode']) {
                        return \Response::json(['status' => 0, 'message' => "Pincode not matched."]);
                    }
                } else {
                    return \Response::json(['status' => 2, 'message' => "Details not found."]);
                }

                $document->api_response_address = $result;
                $document->addressproof = $addressproof;
                $document->current_address = $_POST['house_no'].', '.$_POST['street'].', '.$_POST['landmark'].', '.$_POST['district'].', '.$_POST['pincode'].', '.$_POST['city'].', '.$_POST['state'].', '.$_POST['country'];
                $update = $document->save();
            }
            //echo '1';
            $userKyc_update = User::where('id', $user_id)->first();
            $userKyc_update->kyc_status = 1;
            $userKyc_update->kyc_info = 1;
            $userKyc_update->save();
            return \Response::json(['status' => 1, 'message' => "Kyc updated successfully."]);
            //return redirect('/user/dashboard?kyc=1');
            //return redirect('/user/kyc2');
        } else {
            return \Response::json(['status' => 2, 'message' => "Details not found."]);
            //echo '0';
            //return redirect('/user/dashboard?kyc=0');
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
            $result = json_decode($result);
            
            unset($result->accounts->transactions);
            
            $document = UserKycDocument::where('user_id', $user_id)->first();
            $document->api_response_banking_details = json_encode($result);
            $document->save();
            

            $document = UserKycDocument::where('user_id', $user_id)->first();
            
            /*$random= substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
            $data = [
                'clientRefId' => $random,
                'name_to_match' => 'RAJVIR SECURITIES AND FINANCE PVT LTD',
                'input_name' => $result->customer_info->name
            ];
            $nameComparisonRes = nameComparison($data);*/
            
            return redirect('/user/kyc2');
        } else {
            return redirect('/user/dashboard?kyc=0');
        }
    }
}
