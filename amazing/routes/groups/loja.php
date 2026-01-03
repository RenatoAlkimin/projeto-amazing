<?php

use Illuminate\Support\Facades\Route;

Route::prefix('loja')
    ->as('loja.')
    ->middleware(['web']) // depois: auth
    ->group(function () {
        Route::get('/', function () {
            session(['portal' => 'loja']);
            return 'Portal: LOJA (ok). Agora testa /s/default/comercial';
        })->name('home');
    });
