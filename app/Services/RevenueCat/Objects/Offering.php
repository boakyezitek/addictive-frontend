<?php


namespace App\Services\RevenueCat\Objects;


use App\Services\RevenueCat\Interfaces\ObjectInterface;

class Offering extends BaseObject implements ObjectInterface
{
	private string $currentOfferingId;
    private array $offerings;

    public function __construct(
        string $currentOfferingId,
        array $offerings
    	)

    {
        $this->currentOfferingId = $currentOfferingId;
        $this->offerings = $offerings;
    }

    public static function fromJson(array $json): Offering
    {
        return new Offering(
        	$json['current_offering_id'],
        	$json['offerings'],
        );
    }

    /**
     * @return string
     */
    public function getCurrentOfferingId(): string
    {
        return $this->currentOfferingId;
    }

    /**
     * @return array
     */
    public function getOfferings(): array
    {
        return $this->offerings;
    }
}
