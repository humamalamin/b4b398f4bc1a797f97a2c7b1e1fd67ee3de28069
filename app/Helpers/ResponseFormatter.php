<?php

namespace App\Helpers;

class ResponseFormatter
{
    protected static $response = [
        'code' => 200,
        'status' => 'success',
        'message' => null,
        'data' => null
    ];

    public static function success($data = null, $message = null, $code = 200)
    {
        self::$response['message'] = $message;
        self::$response['code'] = $code;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }

    public static function error($data = null, $message = null, $code = 400)
    {
        self::$response['message'] = $message;
        self::$response['code'] = $code;
        self::$response['status'] = 'error';
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }

}