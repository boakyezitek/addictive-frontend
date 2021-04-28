<?php


namespace App\Services\RevenueCat\Objects;


use App\Services\RevenueCat\Enums\Period;
use App\Services\RevenueCat\Enums\Store;
use App\Services\RevenueCat\Interfaces\ObjectInterface;
use Carbon\Carbon;

class Subscription implements ObjectInterface
{
    private Carbon $expiresDate;
    private Carbon $purchaseDate;
    private Carbon $originalPurchaseDate;
    private Period $period;
    private Store $store;
    private bool $isSandbox;
    private Carbon $unsubscribeDetectedAt;
    private Carbon $billingIssuesDetectedAt;

    public function __construct(Carbon $expiresDate,
                                Carbon $purchaseDate,
                                Carbon $originalPurchaseDate,
                                Period $period,
                                Store $store,
                                bool $isSandbox,
                                Carbon $unsubscribeDetectedAt,
                                Carbon $billingIssuesDetectedAt
    )
    {
        $this->expiresDate = $expiresDate;
        $this->purchaseDate = $purchaseDate;
        $this->originalPurchaseDate = $originalPurchaseDate;
        $this->period = $period;
        $this->store = $store;
        $this->isSandbox = $isSandbox;
        $this->unsubscribeDetectedAt = $unsubscribeDetectedAt;
        $this->billingIssuesDetectedAt = $billingIssuesDetectedAt;
    }

    public static function fromJson(array $json): Subscription
    {
        return new Subscription(
            Carbon::parse($json['expires_date']),
            Carbon::parse($json['purchase_date']),
            Carbon::parse($json['original_purchase_date']),
            new Period($json['period_type']),
            new Store($json['store']),
            $json['is_sandbox'],
            Carbon::parse($json['unsubscribe_detected_at']),
            Carbon::parse($json['billing_issues_detected_at']),
        );
    }
}
