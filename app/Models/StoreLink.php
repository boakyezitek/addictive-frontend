<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'store', 'url', 'linkable_type', 'linkable_id',
    ];

    /**
     * Get the parent linkable model
     */
    public function linkable()
    {
    	return $this->morphTo();
    }
}
