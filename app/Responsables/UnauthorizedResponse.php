<?php

namespace App\Responsables;

class UnauthorizedResponse extends ErrorResponse
{
    public static function create($message = null)
    {
        return parent::makeResponse(401, $message);
    }
}
