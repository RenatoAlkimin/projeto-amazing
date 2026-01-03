<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['module_enabled:rh'])
    ->prefix('rh')
    ->as('rh.')
    ->group(function () {
        Route::get('/', function () {
            return view('modules.rh.index');
        })->name('index');
    });
