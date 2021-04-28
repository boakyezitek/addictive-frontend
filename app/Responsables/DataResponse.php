<?php

namespace App\Responsables;

final class DataResponse
{
    public static function create(array $data, $code = 200)
    {
        return response()->json([
            'data' => $data,
        ], $code);
    }
}
