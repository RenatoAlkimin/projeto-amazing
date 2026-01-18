<?php

use Illuminate\Support\Facades\Route;

Route::prefix('s/{scope}')
    ->middleware(['web', 'auth', 'resolve_portal', 'set_scope', 'ensure_store_exists', 'ensure_scope_access'])
    ->group(function () {
        $modules = array_keys((array) config('modules', []));

        foreach ($modules as $module) {
            $path = __DIR__ . "/../modules/{$module}.php";

            if (! is_file($path)) {
                // Falha cedo: módulo configurado sem arquivo de rotas.
                throw new \RuntimeException(
                    "Arquivo de rotas do módulo '{$module}' não encontrado em {$path}"
                );
            }

            Route::middleware(["module_enabled:{$module}"])
                ->group(function () use ($path) {
                    require $path;
                });
        }
    });
