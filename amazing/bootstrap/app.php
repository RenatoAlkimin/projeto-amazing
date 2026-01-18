<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\ResolvePortal;
use App\Http\Middleware\EnsureModuleEnabled;
use App\Http\Middleware\SetScope;
use App\Http\Middleware\EnsureStoreExists;
use App\Http\Middleware\EnsureScopeAccess;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'resolve_portal' => ResolvePortal::class,
            'module_enabled' => EnsureModuleEnabled::class,
            'set_scope' => SetScope::class,
            'ensure_store_exists' => EnsureStoreExists::class,
            'ensure_scope_access' => EnsureScopeAccess::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
