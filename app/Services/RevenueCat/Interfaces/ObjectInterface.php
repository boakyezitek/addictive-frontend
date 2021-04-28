<?php

namespace App\Services\RevenueCat\Interfaces;

interface ObjectInterface {
    public static function fromJson(array $json) : ObjectInterface;
}
