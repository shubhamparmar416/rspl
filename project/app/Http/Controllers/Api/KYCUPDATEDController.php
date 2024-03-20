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



    // public function digilockerVerifyCheck5()
    // {
    //     $random = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
    //     $client = new \GuzzleHttp\Client([
    //         'verify' => false
    //     ]);

    //     $url = 'https://svcdemo.digitap.work/ent/v1/kyc/get-digilocker-details';
    //     $data = [
    //         'transactionId' => $_POST['transactionId']
    //     ];

    //     $username = '48130178';
    //     $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
    //     $auth = 'Basic ' . base64_encode($username . ':' . $password);

    //     $response = $client->request('POST', $url, [
    //         'headers' => [
    //             'Authorization' => $auth,
    //         ],
    //         'json' => $data

    //     ]);

    //     $result = $response->getBody()->getContents();
    //     if (empty ($result)) {
    //         return \Response::json(['status' => 0, 'data' => []]);
    //     } else {
    //         //Insert data into table
    //         $user_id = \Auth::id();
    //         $document = UserKycDocument::where('user_id', $user_id)->first();

    //         /*$userKyc_update = User::where('id', $user_id)->first();
    //         $userKyc_update->kyc_status = 1;
    //         $userKyc_update->kyc_info = 1;
    //         $userKyc_update->save();*/

    //         $document->api_response_digilocker_status = $result;
    //         $document->save();

    //         return \Response::json(['status' => 1, 'data' => $result]);
    //     }
    // }



    public function kycVerifyStatus()
    {
        try {
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
                        return $this->error([], "Name is not matched.");

                        // return \Response::json(['status' => 0, 'message' => "Name is not matched."]);
                    }
                } elseif ($document->api_response_aadhar_front_img != null && $document->api_response_aadhar_back_img != null) {
                    $api_response_aadhar_front_img = json_decode($document->api_response_aadhar_front_img);

                    if (str_contains($result->customer_info->name, $api_response_aadhar_front_img->result[0]->details->name->value, )) {
                        return $this->error([], "Name is not matched.");

                        // return \Response::json(['status' => 0, 'message' => "Name is not matched."]);
                    }
                } else {
                    return $this->error([], "Details not found.");

                    // return \Response::json(['status' => 2, 'message' => "Details not found."]);
                }

                unset($result->accounts->transactions);

                $document->api_response_banking_details = json_encode($result);
                $document->bank_details_file_name = $filaName;
                $document->save();

                $document = UserKycDocument::where('user_id', $user_id)->first();
                return $this->success([], "Data verified successfully.");

                // return redirect('/user/kyc2');
            } else {
                return $this->success([], "Data not verified.");

                // return redirect('/user/dashboard?kyc=0');
            }
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
        }
    }

    // FUNCTION WHEN GET RESPONSE FROM VKYC API
    public function vkycUpdate(Request $request)
    {
        try {
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
            return $this->success([], 'Response get successsfully!');
        } catch (\Throwable $e) {
            return $this->error([], "Server error");
        }

    }



}
