<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'release_date',
    ];

    protected $casts = [
        'release_date' => 'datetime'
    ];

    /**
     * Get the book that owns the Ebook
     */
    public function book()
    {
    	return $this->hasOne(Book::class);
    }

    /**
     * Get all the links
     */
    public function links()
    {
    	return $this->morphMany(StoreLink::class, 'linkable');
    }
}
