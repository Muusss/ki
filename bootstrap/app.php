<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        // commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // alias middleware route
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);

        // kalau mau jadikan global atau group, bisa pakai:
        // $middleware->append(\App\Http\Middleware\EnsureAdmin::class); // global
        // $middleware->group('adminonly', [\App\Http\Middleware\EnsureAdmin::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
