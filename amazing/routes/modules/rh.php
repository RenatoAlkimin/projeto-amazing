<?php

use Illuminate\Support\Facades\Route;

Route::prefix('rh')
    ->as('rh.')
    ->group(function () {
        Route::get('/', function () {
            return view('modules.rh.index');
        })->name('index');
    });
