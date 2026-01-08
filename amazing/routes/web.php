<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $scope = (string) config('amazing.default_scope', 'default');

    return redirect()->route('hub.index', ['scope' => $scope]);
});

// Portais (rotas de entrada por painel)
require __DIR__ . '/portals/amazing.php';
require __DIR__ . '/portals/franchising.php';
require __DIR__ . '/portals/franqueado.php';
require __DIR__ . '/portals/franqueado_central.php';
require __DIR__ . '/portals/loja.php';

// Módulos escopados (onde o “trabalho” acontece)
require __DIR__ . '/portals/scoped_modules.php';
