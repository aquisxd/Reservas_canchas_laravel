<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Aquí va tu propiedad protegida
    protected $routeMiddleware = [
        // otros middlewares
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
}
