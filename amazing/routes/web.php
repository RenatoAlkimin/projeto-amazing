<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // ✅ fluxo normal passa por um portal (vaapty), amazing só pela rota dele
    return redirect()->route('vaapty.home');
});

// Portais (rotas de entrada por painel)
require __DIR__ . '/portals/amazing.php';

require __DIR__ . '/portals/vaapty.php';

require __DIR__ . '/auth.php';

// Módulos escopados (onde o “trabalho” acontece)
require __DIR__ . '/portals/scoped_modules.php';
