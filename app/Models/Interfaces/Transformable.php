<?php


namespace App\Models\Interfaces;


use League\Fractal\TransformerAbstract;

interface Transformable
{
    public static function transformer() : TransformerAbstract;
}
