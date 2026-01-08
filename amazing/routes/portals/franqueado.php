<?php

use Illuminate\Support\Facades\Route;

Route::prefix('franqueado')
    ->as('franqueado.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', function () {
            session(['portal' => 'franqueado']);
            return 'Portal: FRANQUEADO (ok).';
        })->name('home');
    });
