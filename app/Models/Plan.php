<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use Eventable;

    public const PLATFORM_IOS = 'ios';
    public const PLATFORM_ANDROID = 'android';

    public const PLATFORMS = [
        self::PLATFORM_IOS,
        self::PLATFORM_ANDROID,
    ];

    public const INTERVAL_MONTH = 'monthly';
    public const INTERVAL_YEAR = 'yearly';

    public const INTERVALS = [
        self::INTERVAL_MONTH,
        self::INTERVAL_YEAR
    ];

    protected $fillable = ['name', 'platform', 'reference', 'amount', 'interval', 'interval_count', 'credits'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'reference', 'reference');
    }

}
