<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // ✅ fluxo normal passa por um portal (loja), amazing só pela rota dele
    return redirect()->route('loja.home');
});

// Portais (rotas de entrada por painel)
require __DIR__ . '/portals/amazing.php';
require __DIR__ . '/portals/franchising.php';
require __DIR__ . '/portals/franqueado.php';
require __DIR__ . '/portals/franqueado_central.php';
require __DIR__ . '/portals/loja.php';

// Módulos escopados (onde o “trabalho” acontece)
require __DIR__ . '/portals/scoped_modules.php';
