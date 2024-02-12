<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKYCUPDATEDRequest;
use App\Http\Requests\UpdateKYCUPDATEDRequest;
use App\Models\KYCUPDATED;
use App\Models\UserKycDocument;
use Illuminate\Support\Facades\Http;

class KYCUPDATEDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('user.kycupdated');
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

    public function step1_document()
    {
        // dd($_POST,$_FILES);
        // $data = [
        //     'user_id' => \Auth::user()->id,
        //     'aadhaar_front' => $_POST['aadhar_number'],
        //     'aadhaar_back' =>,
        //     'aadhaar_no' =>,
        //     'pan_front' =>,
        //     'pan_back' =>,
        //     'pan_no' => $_POST['pan-number'],
        // ];
        $create = UserKycDocument::create($data);
        if ($create) {
            return \Response::json(['status' => 1, 'message' => "Data Added Successfully"]);
        } else {
            return \Response::json(['status' => 0, 'message' => "something went wrong please try again later"]);
        }
    }
}
