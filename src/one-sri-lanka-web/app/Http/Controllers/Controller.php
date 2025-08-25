<?php

/* නමෝ බුද්ධාය */

namespace App\Http\Controllers;

use App\Services\ApiCallerService;

abstract class Controller
{

    protected $api;

    public function __construct(ApiCallerService $api)
    {
        $this->api = $api;
    }
    
}
