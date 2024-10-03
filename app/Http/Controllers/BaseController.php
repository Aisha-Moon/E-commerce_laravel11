<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
        
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function sendError($message, $errorMessages = [], $code = 404){
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);  
    }
}


