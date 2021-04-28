<?php


namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface HomeTransformable
{
    public static function homeTransform(Model $model) : array;
}
