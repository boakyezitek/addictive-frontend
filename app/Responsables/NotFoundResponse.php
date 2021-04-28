<?php

namespace App\Responsables;

class NotFoundResponse extends ErrorResponse
{
    public static function create($message = null)
    {
        return parent::makeResponse(404, $message);
    }
}
