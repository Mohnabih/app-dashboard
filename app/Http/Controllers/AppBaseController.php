<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class AppBaseController extends Controller
{
    public function sendResponse($result, $message, $code = 200, $errorCode = 0)
    {
        return Response::json([
            'data' => $result,
            'message' => $message,
            'status' => $code,
            'errorCode' => $errorCode
        ], $code);
    }
}
