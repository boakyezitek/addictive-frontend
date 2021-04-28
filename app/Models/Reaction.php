<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use Eventable;

    const FEELING_SURPRISED = 'surprised';
    const FEELING_LOVE = 'love';
    const FEELING_SAD = 'sad';
    const FEELING_EXCITED = 'excited';
    const FEELING_FUNNY = 'laugh';
    const FEELING_LISTEN = 'listen';

    const FEELINGS = [
        'surprised' => self::FEELING_SURPRISED,
        'love' => self::FEELING_LOVE,
        'sad' => self::FEELING_SAD,
        'excited' => self::FEELING_EXCITED,
        'laugh' => self::FEELING_FUNNY,
        'listen' => self::FEELING_LISTEN,
    ];

    protected $fillable = ['feeling', 'user_id', 'reactionnable_type', 'reactionnable_id'];

    public function reactionable()
    {
        return $this->morphTo();
    }
}
