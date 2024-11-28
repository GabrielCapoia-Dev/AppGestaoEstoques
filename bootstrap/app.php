<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->throttleApi();

        $middleware->alias([
            'permissao' => \App\Http\Middleware\PermissaoMiddleware::class,    // Registra a middleware de permissão personalizada
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
