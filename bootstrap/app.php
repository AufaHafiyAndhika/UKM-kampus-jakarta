<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.status' => \App\Http\Middleware\CheckUserStatus::class,
            'refresh.role' => \App\Http\Middleware\RefreshUserRole::class,
            'handle.csrf' => \App\Http\Middleware\HandleCsrfException::class,
        ]);

        // Add refresh role middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\RefreshUserRole::class,
            \App\Http\Middleware\HandleCsrfException::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
