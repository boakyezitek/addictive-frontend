<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Eventable;

    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_WAITING_CONFIRMATION = 'waiting_confirmation';
    public const STATUS_REFUND = 'refund';

    public const STATUSES = [
        self::STATUS_SUCCESS,
        self::STATUS_FAILED,
        self::STATUS_WAITING_CONFIRMATION,
        self::STATUS_REFUND,
    ];

    protected $fillable = ['user_id', 'audio_book_id', 'status'];

    public function audioBook()
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

}
