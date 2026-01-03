<?php

use Illuminate\Support\Facades\Route;

Route::prefix('franqueado-central')
    ->as('franqueado_central.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', function () {
            session(['portal' => 'franqueado_central']);
            return 'Portal: CENTRAL DO FRANQUEADO (ok).';
        })->name('home');
    });
