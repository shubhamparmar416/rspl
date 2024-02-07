<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKYCUPDATEDRequest;
use App\Http\Requests\UpdateKYCUPDATEDRequest;
use App\Models\KYCUPDATED;
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
        // print_r($data);die;

        switch ($_POST['type']) {
            case('aadhaar'):
                $url = 'https://svcdemo.digitap.work/validation/kyc/v1/aadhaar';
                $data = [
                    'aadhaar' => $_POST['document'],
                    'client_ref_num' => 'test',
                ];
                break;

            case('pan'):
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
        if ($url == '')
            \Response::json(['status' => 0, 'data' => []]);
        $username = '48130178';
        $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
        $auth = 'Basic ' . base64_encode($username . ':' . $password);

        $response = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => $auth,
            ],
            'json' => $data

        ]);
        return \Response::json(['status' => 1, 'data' => $response->getBody()->getContents()]);

    }
}
