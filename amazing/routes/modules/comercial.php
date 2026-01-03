<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Comercial\ComercialController;

Route::prefix('comercial')
    ->as('comercial.')
    ->middleware(['module_enabled:comercial'])
    ->group(function () {
        Route::get('/', [ComercialController::class, 'index'])->name('index');
    });
