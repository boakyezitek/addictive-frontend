<?php

namespace App\Responsables;

class HttpResponse
{
    public static function makeResponse($code = 200, $message = null)
    {
        return $message === null ? response(null, $code) : response()->json([
            'message' => $message,
        ], $code);
    }

    public static function json(array $data, $code = 200)
    {
        return response()->json($data, $code);
    }

    public static function create($message = null)
    {
        return self::makeResponse(200, $message);
    }
}
