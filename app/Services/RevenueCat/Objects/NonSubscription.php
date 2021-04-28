<?php


namespace App\Services\RevenueCat\Objects;


use App\Services\RevenueCat\Enums\Store;
use App\Services\RevenueCat\Interfaces\ObjectInterface;
use Carbon\Carbon;

class NonSubscription extends BaseObject implements ObjectInterface
{
    private string $id;
    private Carbon $purchaseDate;
    private Store $store;
    private bool $isSandbox;

    public function __construct(string $id,
                                Carbon $purchaseDate,
                                Store $store,
                                bool $isSandbox)
    {
        $this->id = $id;
        $this->purchaseDate = $purchaseDate;
        $this->store = $store;
        $this->isSandbox = $isSandbox;
    }

    public static function fromJson(array $json): ObjectInterface
    {
        return new NonSubscription(
            $json['id'],
            Carbon::parse($json['purchase_date']),
            new Store($json['store']),
            $json['is_sandbox'],
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Carbon
     */
    public function getPurchaseDate(): Carbon
    {
        return $this->purchaseDate;
    }

    /**
     * @return Store
     */
    public function getStore(): Store
    {
        return $this->store;
    }

    /**
     * @return bool
     */
    public function isSandbox(): bool
    {
        return $this->isSandbox;
    }
}
