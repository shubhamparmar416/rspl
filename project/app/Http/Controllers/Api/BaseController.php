<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    public function success($data = [], $message = '')
    {
        $success = config('constants.api.SUCCESS');
        return response()->json(array('status' => 1 ,'code' => $success, 'data' => $data, 'errors' => [], 'message' => $message));
    }

    public function error($errors = [],$message = '')
    {
        $error = config('constants.api.ERROR');
        return response()->json(array('status' => 0 ,'code' => $error, 'data' => [], 'errors' => $errors, 'message' => $message));
    }


}

?>