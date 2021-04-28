<?php

namespace App\Responsables;

class EmptyResponse extends HttpResponse
{
    public static function create($message = null)
    {
        return parent::create($message = null);
    }
}
