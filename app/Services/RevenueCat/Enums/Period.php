<?php


namespace App\Services\RevenueCat\Enums;


use MyCLabs\Enum\Enum;

/**
 * @method static self NORMAL()
 * @method static self TRIAL()
 * @method static self INTRO()
 */
class Period extends Enum
{
    private const NORMAL = 'normal'; // The product is in it's normal period (default)
    private const TRIAL = 'trial'; // The product is in a free trial period
    private const INTRO = 'intro'; // The product is in an introductory pricing period
}
