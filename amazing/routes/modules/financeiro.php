<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Financeiro\FinanceiroController;

Route::prefix('financeiro')
    ->as('financeiro.')
    ->group(function () {
        Route::get('/', [FinanceiroController::class, 'index'])->name('index');
    });
