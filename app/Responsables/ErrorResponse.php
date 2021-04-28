<?php

namespace App\Responsables;

class ErrorResponse extends HttpResponse
{
    public static function create($message = null)
    {
        return parent::makeResponse(400, $message);
    }
}
