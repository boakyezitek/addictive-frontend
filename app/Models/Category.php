<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\Transformable;
use App\Transformers\V1\CategoryTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model implements Transformable
{
    use HasFactory, Eventable;

    protected $fillable = ['name'];

    public static function transformer() : TransformerAbstract
    {
        return new CategoryTransformer();
    }

    public function audioBooks()
    {
        return $this->belongsToMany(AudioBook::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
