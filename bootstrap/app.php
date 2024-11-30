<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

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
        $exceptions->respond(function (Response $response) {
//            if ($response->getStatusCode() === 419) {
//                return back()->with([
//                    'message' => 'The page expired, please try again.',
//                ]);
//            }

            $statusCode = $response->getStatusCode() ?: 500;
            $myresponse = [
                'error' => $response,
                'status_code' => $statusCode,
            ];

            return response($myresponse, $statusCode);
        });
    })->create();
