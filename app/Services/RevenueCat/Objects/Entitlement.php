<?php


namespace App\Services\RevenueCat\Objects;

use App\Services\RevenueCat\Interfaces\ObjectInterface;
use Carbon\Carbon;

class Entitlement extends BaseObject implements ObjectInterface
{
    private Carbon $expiresDate;
    private Carbon $purchaseDate;
    private string $productIdentifier;

    public function __construct(Carbon $expiresDate, Carbon $purchaseDate, string $productIdentifier)
    {
        $this->expiresDate = $expiresDate;
        $this->purchaseDate = $purchaseDate;
        $this->productIdentifier = $productIdentifier;
    }

    public static function fromJson(array $json): ObjectInterface
    {
        return new Entitlement(
            Carbon::parse($json['expires_date']),
            Carbon::parse($json['purchase_date']),
            $json['product_identifier'],
        );
    }

    /**
     * @return Carbon
     */
    public function getExpiresDate(): Carbon
    {
        return $this->expiresDate;
    }

    /**
     * @return Carbon
     */
    public function getPurchaseDate(): Carbon
    {
        return $this->purchaseDate;
    }

    /**
     * @return string
     */
    public function getProductIdentifier(): string
    {
        return $this->productIdentifier;
    }
}
