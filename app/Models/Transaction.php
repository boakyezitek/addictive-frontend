<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    use Eventable;

    public const CLASS_CREDIT_PURCHASE = CreditPurchase::class;
    public const CLASS_SUBSCRIPTION = Subscription::class;

    public const SLUG_CREDIT_PURCHASE = 'credit_purchases';
    public const SLUG_SUBSCRIPTION = 'subscriptions';

    public const TRANSACTIONABLE_CLASS_SLUG = [
        self::CLASS_CREDIT_PURCHASE => self::SLUG_CREDIT_PURCHASE,
        self::CLASS_SUBSCRIPTION => self::SLUG_SUBSCRIPTION,
    ];

    public function transactionable() {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->transactionable->user;
    }

    public function status()
    {
        return $this->transactionable->status;
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
