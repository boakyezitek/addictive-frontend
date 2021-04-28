<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    use Eventable;

    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PAUSED = 'paused';
    public const STATUS_WAITING_CONFIRMATION = 'waiting_confirmation';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_REFUND = 'refund';

    public const STATUSES = [
        self::STATUS_CANCELED,
        self::STATUS_PAUSED,
        self::STATUS_WAITING_CONFIRMATION,
        self::STATUS_IN_PROGRESS,
        self::STATUS_REFUND
    ];

    protected $fillable = ['user_id', 'reference', 'transaction_id', 'price', 'currency', 'purchased_at', 'expiration_at', 'status', 'renewed_at', 'renewed_count', 'interval', 'cancelled_at', 'period_type'];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expiration_at' => 'datetime',
        'renewed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

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
        return $query->where('status', self::STATUS_IN_PROGRESS)->where('expiration_at', '<', Carbon::now())->whereNull('cancelled_at');
    }

    public function createCredits()
    {
        if($this->status == self::STATUS_IN_PROGRESS) {
            if ($this->interval == "monthly") {
                $this->credits()->create([
                    'user_id' => $this->user_id,
                    'expire_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->purchased_at)->addMonths(6),
                    'available_at' => Carbon::now(),
                    'purchased_price' => $this->price,
                    'currency' => $this->currency,
                ]);
            } elseif ($this->interval == "yearly") {
                $i = 0;
                if (config('app.env') == "development" || config('app.env') == "staging" || config('app.env') == "local") {
                    do {
                        $this->credits()->create([
                            'user_id' => $this->user_id,
                            'expire_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->purchased_at)->addMinutes($i * 5)->addMonths(6),
                            'available_at' => Carbon::now()->addMinutes($i * 5),
                            'purchased_price' => $this->price/12,
                            'currency' => $this->currency,
                        ]);
                        $i += 1;
                    } while ($i < 12);
                } else {
                    do {
                        $this->credits()->create([
                            'user_id' => $this->user_id,
                            'expire_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->purchased_at)->addMonths(6 + $i),
                            'available_at' => Carbon::now()->addMonths($i),
                            'purchased_price' => $this->price/12,
                            'currency' => $this->currency,
                        ]);
                        $i += 1;
                    } while ($i < 12);
                }
            }
        }
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'reference', 'reference');
    }
}
