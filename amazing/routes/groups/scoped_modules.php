<?php

use Illuminate\Support\Facades\Route;

Route::prefix('s/{scope}')
    ->middleware(['web', 'resolve_portal', 'set_scope']) // depois: auth
    ->group(function () {
        foreach (['hub', 'comercial', 'financeiro', 'marketing', 'rh'] as $module) {
            require_once __DIR__ . "/../modules/{$module}.php";
        }
    });
