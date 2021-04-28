<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['eventable_type', 'eventable_id', 'owner_type', 'owner_id', 'action', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventable()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
