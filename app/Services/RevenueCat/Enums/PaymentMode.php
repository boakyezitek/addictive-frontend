<?php


namespace App\Services\RevenueCat\Enums;


use MyCLabs\Enum\Enum;

/**
 * @method static self PAY_AS_YOU_GO()
 * @method static self PAY_UP_FRONT()
 * @method static self FREE_TRIAL()
 */
class PaymentMode extends Enum
{
    private const PAY_AS_YOU_GO = 0;
    private const PAY_UP_FRONT = 1;
    private const FREE_TRIAL = 2;
}
