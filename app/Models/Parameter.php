<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Transformers\V1\ParameterTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = ['about', 'privacy', 'faq', 'terms'];

    public static function transformer(): TransformerAbstract
    {
        return new ParameterTransformer();
    }
}
