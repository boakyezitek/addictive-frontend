<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class CreditPack extends Model
{
    use Eventable;

    public const PLATFORM_IOS = 'ios';
    public const PLATFORM_ANDROID = 'android';
    public const PLATFORM_WEB = 'web';

    public const PLATFORMS = [
        self::PLATFORM_IOS,
        self::PLATFORM_ANDROID,
        self::PLATFORM_WEB
    ];

    protected $fillable = ['name', 'platform', 'reference', 'amount', 'credits'];

    public function purchases()
    {
        return $this->hasMany(CreditPurchase::class);
    }
}
