<?php

namespace App\Services\RevenueCat;


class Endpoints {

    public static function getOrCreateSubscriberUrl(int $userId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/".$userId;
    }

    public static function updateSubscriberAttributesUrl(int $userId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/".$userId.'/attributes';
    }

    public static function deleteSubscriberUrl(int $userId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/".$userId;
    }

    public static function createPurchaseUrl(): string
    {
        return "https://api.revenuecat.com/v1/receipts";
    }

    public static function grantPromotionalEntitlementUrl(int $userId, string $entitlementId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/entitlements/$entitlementId/promotional";
    }

    public static function revokePromotionalEntitlementUrl(int $userId, string $entitlementId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/entitlements/$entitlementId/revoke_promotionals";
    }

    public static function refundGoogleSubscriptionUrl(int $userId, string $productId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/subscriptions/$productId/revoke";
    }

    public static function deferGoogleSubscriptionUrl(int $userId, string $productId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/subscriptions/$productId/defer";
    }

    public static function addUserAttributionUrl(int $userId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/attribution";
    }

    public static function getOfferingUrl(int $userId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/offerings";
    }

    public static function overrideSubscriberCurrentOfferingUrl(int $userId, string $offeringId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/offerings/$offeringId";
    }

    public static function deleteSubscriberCurrentOfferingOverrideUrl(int $userId): string
    {
        return "https://api.revenuecat.com/v1/subscribers/$userId/offerings/override";
    }

}
