<?php


namespace App\Services\RevenueCat\Enums;


use MyCLabs\Enum\Enum;

/**
 * @method static self DAILY()
 * @method static self WEEKLY()
 * @method static self MONTHLY()
 * @method static self TWO_MONTH()
 * @method static self THREE_MONTH()
 * @method static self SIX_MONTH()
 * @method static self YEARLY()
 * @method static self LIFETIME()
 */
class Duration extends Enum
{
    private const DAILY = 'daily';
    private const WEEKLY = 'weekly';
    private const MONTHLY = 'monthly';
    private const TWO_MONTH = 'two_month';
    private const THREE_MONTH = 'three_month';
    private const SIX_MONTH = 'six_month';
    private const YEARLY = 'yearly';
    private const LIFETIME = 'lifetime';
}
