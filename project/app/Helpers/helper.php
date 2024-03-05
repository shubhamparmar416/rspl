<?php

use App\Models\Currency;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

if (!function_exists('globalCurrency')) {
    function globalCurrency()
    {
        $currency = Session::get('currency') ?  DB::table('currencies')->where('id', '=', Session::get('currency'))->first() : DB::table('currencies')->where('is_default', '=', 1)->first();
        return $currency;
    }
}

if (!function_exists('showPrice')) {
    function showPrice($price, $currency)
    {
        $gs = Generalsetting::first();

        $price = round(($price) * $currency->value, 2);
        if ($gs->currency_format == 0) {
            return $currency->sign. $price;
        } else {
            return $price. $currency->sign;
        }
    }
}

if (!function_exists('showNameAmount')) {
    function showNameAmount($amount)
    {
        $gs = Generalsetting::first();
        $currency = globalCurrency();

        $price = round(($amount) * $currency->value, 2);
        if ($gs->currency_format == 0) {
            return $currency->name.' '. $price;
        } else {
            return $price.' '. $currency->name;
        }
    }
}

if (!function_exists('showAmountSign')) {
    function showAmountSign($amount)
    {
        $gs = Generalsetting::first();
        $currency = globalCurrency();

        $price = round(($amount) * $currency->value, 2);
        if ($gs->currency_format == 0) {
            return $currency->name.' '. $price;
        } else {
            return $price.' '. $currency->sign;
        }
    }
}

if (!function_exists('convertedAmount')) {
    function convertedAmount($price)
    {
        $currency = globalCurrency();

        $price = round(($price) * $currency->value, 2);
        return $price;
    }
}

if (!function_exists('baseCurrencyAmount')) {
    function baseCurrencyAmount($amount)
    {
        $currency = globalCurrency();
        return $amount/$currency->value;
    }
}

if (!function_exists('convertedPrice')) {
    function convertedPrice($price, $currency)
    {
        return $price = $price * $currency->value;
    }
}

if (!function_exists('defaultCurr')) {
    function defaultCurr()
    {
        return Currency::where('is_default', '=', 1)->first();
    }
}

    // Below function is used to verify aadhar and pan card image from third party api
if (!function_exists('imageVerification')) {
    function imageVerification($endpoint, $contents)
    {
        $url = 'https://apidemo.digitap.work/ocr/v1/';
        //$url = 'https://api.digitap.ai/ocr/v1/';

        // Authentication credentials
        $username = '48130178';
        $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
        /*$username = '34469987';
        $password = 'RSzF0t3ZQNhqGqqtTyFrMmEMqxpTK728';*/

        // Data to be sent (if any)
        $data = array(
            'outputmaskedAadhaar' => 'yes',
            'clientRefId' => 'test',
            'isBlackWhiteCheck' => 'yes',
            'confidence' => 'yes',
            // 'maskAadhaarNumber' => 'yes',
            'fraudCheck' => 'yes',
            'imageUrl' => base64_encode($contents)
        );

        try {
            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url . $endpoint); // Set URL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
            curl_setopt($ch, CURLOPT_POST, true); // Set as POST request

            // Set data (if any)
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }

            // Set Basic Authentication
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

            // Execute cURL request
            $response = curl_exec($ch);
            //dd($response);
            $responseData = array();
            // Check for errors
            if ($response === false) {
                echo 'Error: ' . curl_error($ch);
                 $responseData['status'] = "error";
                $responseData['response'] = curl_error($ch);
            } else {
                // Output the response
                $responseData['status'] = "sucess";
                $responseData['response'] = $response;
            }

            // Close cURL session
            curl_close($ch);
            return  $responseData;
        } catch (Exception $e) {
            return $responseData['status'] = "error";
            $responseData['response'] = $e->getMessage();
        }
    }
} // END imageVerification

    // Below function is used to verify name from third party api
if (!function_exists('nameComparison')) {
    function nameComparison($data)
    {
        $url = 'https://apidemo.digitap.work/ent/v1/name_match';
        $username = '48130178';
        $password = '6RBkqcF5iarvmWeK5pLhjXrvfcEC8FLe';
        /*$url = 'https://api.digitap.ai/ent/v1/name_match';
        $username = '34469987';
        $password = 'RSzF0t3ZQNhqGqqtTyFrMmEMqxpTK728';*/
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . $endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }

            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

            $response = curl_exec($ch);
            //dd($response);
            $responseData = array();
            // Check for errors
            if ($response === false) {
                 $responseData['status'] = "error";
                $responseData['response'] = curl_error($ch);
            } else {
                $responseData['status'] = "sucess";
                $responseData['response'] = $response;
            }
            curl_close($ch);
            return  $responseData;
        } catch (Exception $e) {
            return $responseData['status'] = "error";
            $responseData['response'] = $e->getMessage();
        }
    }
} // END imageVerification
