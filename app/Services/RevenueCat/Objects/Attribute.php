<?php


namespace App\Services\RevenueCat\Objects;


use App\Services\RevenueCat\Interfaces\ObjectInterface;

class Attribute implements ObjectInterface
{
    private string $value;
    private int $updatedAtMs;

    public static function fromJson(array $json): ObjectInterface
    {
        // TODO: Implement fromJson() method.
    }
}
