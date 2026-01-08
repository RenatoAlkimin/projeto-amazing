<?php

use Illuminate\Support\Facades\Route;

Route::prefix('franchising')
    ->as('franchising.')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', function () {
            session(['portal' => 'franchising']);
            return 'Portal: FRANCHISING (ok).';
        })->name('home');
    });
