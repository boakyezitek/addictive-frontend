<?php

namespace App\Services\RevenueCat\Interfaces;

use App\Services\RevenueCat\Objects\Entitlement;
use App\Services\RevenueCat\Objects\Offering;
use App\Services\RevenueCat\Objects\Purchase;
use App\Services\RevenueCat\Objects\Response;
use App\Services\RevenueCat\Objects\Subscriber;

interface RevenueCatInterface {
    public function getOrCreateSubscriber(int $userId): Response;

    public function updateSubscriberAttributes(int $userId, array $attributes): Subscriber;

    public function deleteSubscriber(int $userId): bool;

    public function createPurchase(): Purchase;

    public function grantPromotionalEntitlement(int $userId, string $entitlementId): Entitlement;

    public function revokePromotionalEntitlement(int $userId, string $entitlementId): bool;

    public function refundGoogleSubscription(int $userId, string $productId): bool;

    public function deferGoogleSubscription(int $userId, string $productId): bool;

    public function addUserAttribution(int $userId): Response;

    public function getOffering(int $userId): Offering;

    public function overrideSubscriberCurrentOffering(int $userId, string $offeringId): Offering;

    public function deleteSubscriberCurrentOfferingOverride(int $userId): bool;
}
