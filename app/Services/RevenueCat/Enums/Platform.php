<?php


namespace App\Services\RevenueCat\Enums;


use MyCLabs\Enum\Enum;

/**
 * @method static self IOS()
 * @method static self ANDROID()
 * @method static self MACOS()
 * @method static self UIKITFORMAC()
 */
class Platform extends Enum
{
    private const IOS = 'ios';
    private const ANDROID = 'android';
    private const MACOS = 'macos';
    private const UIKITFORMAC = 'uikitformac';
}
