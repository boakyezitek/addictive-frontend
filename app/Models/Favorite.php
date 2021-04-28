<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use Eventable;

    public function favoritable() {
        return $this->morphTo();
    }
}
