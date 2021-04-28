<?php


namespace App\Services\RevenueCat\Objects;


use App\Services\RevenueCat\Interfaces\ObjectInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Subscriber extends BaseObject implements ObjectInterface
{
    private int $originalAppUserId;
    private ?string $originalApplicationVersion; // Only available on iOS
    private Carbon $firstSeen;
    private Carbon $lastSeen;
    private Collection $entitlements;
    private Collection $subscriptions;
    private Collection $nonSubscriptions;
    private ?Collection $subscriberAttributes; // Only included in requests made with secret keys.

    public function __construct(
        int $originalAppUserId,
        ?string $originalApplicationVersion,
        Carbon $firstSeen,
        Carbon $lastSeen,
        Collection $entitlements,
        Collection $subscriptions,
        Collection $nonSubscriptions,
        ?Collection $subscriberAttributes)
    {
        $this->originalAppUserId = $originalAppUserId;
        $this->originalApplicationVersion = $originalApplicationVersion;
        $this->firstSeen = $firstSeen;
        $this->lastSeen = $lastSeen;
        $this->entitlements = $entitlements;
        $this->subscriptions = $subscriptions;
        $this->nonSubscriptions = $nonSubscriptions;
        $this->subscriberAttributes = $subscriberAttributes;
    }

    public static function fromJson(array $json) : Subscriber
    {
        return new Subscriber(
            $json['original_app_user_id'],
            $json['original_application_version'],
            Carbon::parse($json['first_seen']),
            Carbon::parse($json['last_seen']),
            collect(array_map(fn ($object) => Entitlement::fromJson($object), $json['entitlements'])),
            collect(array_map(fn ($object) => Subscription::fromJson($object), $json['subscriptions'])),
            collect(array_map(function ($collection) {
                return collect(array_map(fn ($object) => NonSubscription::fromJson($object), $collection));
            }, $json['non_subscriptions'])),
            array_key_exists('subscriber_attributes', $json) ?
                collect(array_map(fn ($object) => Attribute::fromJson($object), $json['subscriber_attributes']))
                : null,
        );
    }

    /**
     * @return int
     */
    public function getOriginalAppUserId(): int
    {
        return $this->originalAppUserId;
    }

    /**
     * @return string|null
     */
    public function getOriginalApplicationVersion(): ?string
    {
        return $this->originalApplicationVersion;
    }

    /**
     * @return Carbon
     */
    public function getFirstSeen(): Carbon
    {
        return $this->firstSeen;
    }

    /**
     * @return Carbon
     */
    public function getLastSeen(): Carbon
    {
        return $this->lastSeen;
    }

    /**
     * @return Collection
     */
    public function getEntitlements(): Collection
    {
        return $this->entitlements;
    }

    /**
     * @return Collection
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    /**
     * @return Collection
     */
    public function getNonSubscriptions(): Collection
    {
        return $this->nonSubscriptions;
    }

    /**
     * @return Collection|null
     */
    public function getSubscriberAttributes(): ?Collection
    {
        return $this->subscriberAttributes;
    }
}
