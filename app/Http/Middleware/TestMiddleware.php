<?php

namespace App\Http\Middleware;

class TestMiddleware extends Middleware
{
    function __construct()
    {
        parent::__construct();
        echo 'Test Middleware';
    }
}
