<?php


namespace App\Services\RevenueCat\Facades;


use Illuminate\Support\Facades\Facade;

class RevenueCatFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'revenue-cat';
    }
}
