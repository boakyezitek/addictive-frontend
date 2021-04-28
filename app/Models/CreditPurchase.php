<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class CreditPurchase extends Model
{

    use Eventable;

    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUND = 'refund';

    public const STATUSES = [
        self::STATUS_SUCCESS,
        self::STATUS_FAILED,
        self::STATUS_REFUND,
    ];

    use Eventable;

    protected $fillable = ['user_id', 'credit_pack_id', 'status'];

    public function creditPack()
    {
        return $this->belongsTo(CreditPack::class);
    }

    public function credits()
    {
        return $this->morphMany(Credit::class, 'creditable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function getTransactionNameAttribute()
    {
        return $this->creditPack->name;
    }
}
