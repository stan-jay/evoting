<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'admin' => EnsureUserIsAdmin::class,
            'active' => EnsureUserIsActive::class,
            ]);
        })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (QueryException $e) {
            $message = $e->getMessage();

            if (
                str_contains($message, 'SQLSTATE[08006]')
                || str_contains($message, 'SQLSTATE[HY000] [2002]')
                || str_contains(strtolower($message), 'connection refused')
            ) {
                return response()->view('errors.database-unavailable', [], 503);
            }

            return null;
        });

        $exceptions->render(function (\PDOException $e) {
            $message = $e->getMessage();

            if (
                str_contains($message, 'SQLSTATE[08006]')
                || str_contains($message, 'SQLSTATE[HY000] [2002]')
                || str_contains(strtolower($message), 'connection refused')
            ) {
                return response()->view('errors.database-unavailable', [], 503);
            }

            return null;
        });
    })->create();
