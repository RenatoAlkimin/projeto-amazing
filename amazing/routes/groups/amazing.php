<?php

use Illuminate\Support\Facades\Route;

Route::prefix('amazing')
    ->as('amazing.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', function () {
            session(['portal' => 'amazing']);
            return 'Portal: AMAZING (ok). Acesso total.';
        })->name('home');
    });
