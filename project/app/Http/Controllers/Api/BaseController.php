<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    public function success($data = [], $message = '')
    {
        $success = config('constants.api.SUCCESS');
        return response()->json(array('code' => $success, 'data' => $data, 'errors' => [], 'message' => $message));
    }

    public function error($errors)
    {
        $error = config('constants.api.ERROR');
        return response()->json(array('code' => $error, 'data' => [], 'errors' => $errors, 'message' => ''));
    }


}

?>