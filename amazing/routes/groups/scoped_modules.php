<?php

use Illuminate\Support\Facades\Route;

Route::prefix('s/{scope}')
    ->middleware(['web', 'resolve_portal', 'set_scope']) // depois: auth
    ->group(function () {
        require_once __DIR__.'/../modules/hub.php';
        require_once __DIR__.'/../modules/comercial.php';
        require_once __DIR__.'/../modules/financeiro.php';
        require_once __DIR__.'/../modules/central.php';
    });
