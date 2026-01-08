<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Diagnostics\DiagnosticsController;

Route::prefix('diagnostics')
    ->as('diagnostics.')
    ->group(function () {
        Route::get('/', [DiagnosticsController::class, 'index'])->name('index');
    });
