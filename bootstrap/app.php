<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exception = $exceptions[0];
        $statusCode = $exception->getStatusCode() ?: 400;

        $response = [
            'error' => $exception->getMessage(),
            'status_code' => $statusCode,
        ];

        if (app()->environment('local')) {
            $response['trace'] = $exception->getTrace();
        }

        return response($response, $statusCode);
    })->create();
