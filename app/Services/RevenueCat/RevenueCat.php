<?php


namespace App\Services\RevenueCat;

use App\Services\RevenueCat\Interfaces\RevenueCatInterface;
use App\Services\RevenueCat\Objects\Entitlement;
use App\Services\RevenueCat\Objects\Offering;
use App\Services\RevenueCat\Objects\Purchase;
use App\Services\RevenueCat\Objects\Response;
use App\Services\RevenueCat\Objects\Subscriber;

class RevenueCat implements RevenueCatInterface
{
    private ApiManager $apiManager;

    public function __construct(string $apiKey)
    {
        $this->apiManager = new ApiManager($apiKey);
    }

    public function getOrCreateSubscriber(int $userId): Response
    {
        return $this->apiManager->get(Endpoints::getOrCreateSubscriberUrl($userId), function(array $json) {
            return Response::fromJson($json);
        });
    }

    public function updateSubscriberAttributes(int $userId, array $attributes): Subscriber
    {
        return $this->apiManager->post(Endpoints::updateSubscriberAttributesUrl($userId), $attributes, function(array $json) {
            return Subscriber::fromJson($json);
        });
    }

    public function deleteSubscriber(int $userId): bool
    {
        return $this->apiManager->delete(Endpoints::deleteSubscriberCurrentOfferingOverrideUrl($userId), function(array $json){
            return $json['deleted'];
        });
    }

    public function createPurchase(): Purchase
    {
        return new Purchase();
    }

    public function grantPromotionalEntitlement(int $userId, string $entitlementId): Entitlement
    {
        return new Entitlement();
    }

    public function revokePromotionalEntitlement(int $userId, string $entitlementId): bool
    {
        return false;
    }

    public function refundGoogleSubscription(int $userId, string $productId): bool
    {
        // TODO: Implement refundGoogleSubscription() method.
    }

    public function deferGoogleSubscription(int $userId, string $productId): bool
    {
        // TODO: Implement deferGoogleSubscription() method.
    }

    public function addUserAttribution(int $userId): Response
    {
        // TODO: Implement addUserAttribution() method.
    }

    public function getOffering(int $userId): Offering
    {
        return $this->apiManager->setPlatform('ios')->get(Endpoints::getOfferingUrl($userId), function(array $json) {
            return Offering::FromJson($json);
        });
    }

    public function overrideSubscriberCurrentOffering(int $userId, string $offeringId): Offering
    {
        // TODO: Implement overrideSubscriberCurrentOffering() method.
    }

    public function deleteSubscriberCurrentOfferingOverride(int $userId): bool
    {
        // TODO: Implement deleteSubscriberCurrentOfferingOverride() method.
    }
}
