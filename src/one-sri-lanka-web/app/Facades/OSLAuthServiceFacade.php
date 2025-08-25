<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OSLAuthServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        // This string is the container binding key for the service
        return 'oslauthservice';
    }
}
