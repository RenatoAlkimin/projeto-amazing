<?php

use Illuminate\Support\Facades\Route;

Route::prefix('marketing')
    ->as('marketing.')
    ->group(function () {
        Route::get('/', function () {
            return view('modules.marketing.index');
        })->name('index');
    });
