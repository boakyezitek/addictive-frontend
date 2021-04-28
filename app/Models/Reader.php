<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    use HasFactory, Eventable;

    protected $fillable = ['first_name', 'last_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
    
    public function audioBooks()
    {
        return $this->belongsToMany(AudioBook::class);
    }
}
