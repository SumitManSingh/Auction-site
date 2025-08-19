<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class, // <-- ADD THIS LINE
            // 'auth' => \App\Http\Middleware\Authenticate::class, // 'auth' is usually aliased by default
            // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // 'guest' is usually aliased by default
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
