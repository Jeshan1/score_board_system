<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function successResponse($msg, $data=[], $code = 200)
    {
        $response = [
            'message' => $msg,
            'data' => $data,
            'status_code' => $code
        ];
        return response()->json($response);
    }

    public function errorResponse($msg, $code=500)
    {
        $response = [
            'message' => $msg,
            'status_code' => $code
        ];
        return response()->json($response);
    }

   
}
