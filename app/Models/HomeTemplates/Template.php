<?php


namespace App\Models\HomeTemplates;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Template
{
    public static string $key;
    public abstract  static function name() : string;
    public abstract static function fields() : array;
    public abstract static function transformer(Model $model): array;
    public abstract static function data(Model $model);
}
