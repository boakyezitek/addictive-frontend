<?php

namespace App\Responsables;

class ForbiddenResponse extends ErrorResponse
{
    public static function create($message = null)
    {
        return parent::makeResponse(403, $message);
    }
}
