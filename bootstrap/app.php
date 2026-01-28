<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'guest.token' => \App\Http\Middleware\GuestTokenMiddleware::class,
        ]);

        // Exclude API routes and admin routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'admin/login',
            'admin/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Ensure API routes always return JSON responses
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
    })->create();