<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['module_enabled:marketing'])
    ->prefix('marketing')
    ->as('marketing.')
    ->group(function () {
        Route::get('/', function () {
            return view('modules.marketing.index');
        })->name('index');
    });
