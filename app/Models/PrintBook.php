<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrintBook extends Model
{
    use HasFactory;

    protected $table = 'prints';

    protected $fillable = [
        'release_date', 'page_count', 'isbn',
    ];

    protected $casts = [
        'release_date' => 'datetime'
    ];

    /**
     * Get the book that owns the PrintBook
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
