<?php

<<<<<<< HEAD
=======
use App\Http\Middleware\Check404;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
<<<<<<< HEAD
=======

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
        $middleware->encryptCookies(except: ['appearance']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
<<<<<<< HEAD
=======

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
