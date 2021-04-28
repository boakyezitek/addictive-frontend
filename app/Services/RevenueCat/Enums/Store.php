<?php


namespace App\Services\RevenueCat\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self APP_STORE()
 * @method static self MAC_APP_STORE()
 * @method static self PLAY_STORE()
 * @method static self STRIPE()
 * @method static self PROMOTIONAL()
 */
class Store extends Enum
{
    private const APP_STORE = 'app_store'; // The product was purchased through Apple App Store.
    private const MAC_APP_STORE = 'mac_app_store'; // The product was purchased through the Mac App Store.
    private const PLAY_STORE = 'play_store'; // The product was purchased through the Google Play Store.
    private const STRIPE = 'stripe'; // The product was purchased through Stripe.
    private const PROMOTIONAL = 'promotional'; // The product was granted via RevenueCat.
}
