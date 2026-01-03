<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Central\CentralController;

Route::prefix('central')
    ->as('central.')
    ->middleware(['module_enabled:central'])
    ->group(function () {
        Route::get('/', [CentralController::class, 'index'])->name('index');
    });
