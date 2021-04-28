<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory, Eventable;

    protected $fillable = ['name', 'code'];

    public function audioBooks()
    {
        return $this->hasMany(AudioBook::class);
    }
}
