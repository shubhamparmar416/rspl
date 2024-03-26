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

class EnachController extends Controller
{

    // GET ACTIVE BANK LIST
    public function activeBank()
    {

        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            $data = array();
            $user_id = \Auth::id();
            Log::info(base64_encode($user_id) . " ====> Call api for active bank ");

            // API CALL PARAMS
            $url = env('KYC_API_URL') . "/ent/v1/enach/active-bank-list";
            $username = env('KYC_CLIENT_ID');
            $password = env('KYC_CLIENT_PASSWORD');
            $auth = 'Basic ' . base64_encode($username . ':' . $password);

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => $auth,
                    'ent_authorization' => env('KYC_CLIENT_TOKEN')
                ],
                'json' => $data

            ]);

            // GET RESULT FROM API
            $result = $response->getBody()->getContents();
            if (empty ($result)) {
                Log::error(base64_encode($user_id) . " ====> Call api for ENACH active bank result is empty");
                return $this->error([], "Something went wrong");
                // return \Response::json(['status' => 0, 'data' => [], 'message' => "Something went wrong"]);
            } else {
                $data = json_decode($result);
                return $this->success($result);
            }

        } catch (\Throwable $e) {
            Log::error(base64_encode($user_id) . " ====> Call api for ENACH active bank result is in catch block" . json_encode($e));
            return $this->error([], "Server error");
        }
    }
    // GET INTIATE BANK LIST
    public function intiate(Request $request)
    {

        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            $data = array();
            $user_id = \Auth::id();
            Log::info(base64_encode($user_id) . " ====> Call api for initiate ENACH ");

            // API CALL PARAMS
            $url = env('KYC_API_URL') . "/ent/v1/enach//initiate-enach";
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
                Log::error(base64_encode($user_id) . " ====> Call api for ENACH active bank result is empty");
                return $this->error([], "Something went wrong");
                // return \Response::json(['status' => 0, 'data' => [], 'message' => "Something went wrong"]);
            } else {
                $data = json_decode($result);
                return $this->success($result);
            }

        } catch (\Throwable $e) {
            Log::error(base64_encode($user_id) . " ====> Call api for ENACH active bank result is in catch block" . json_encode($e));
            return $this->error([], "Server error");
        }
    }


}
