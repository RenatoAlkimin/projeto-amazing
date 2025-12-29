<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HubController;
use App\Http\Controllers\Comercial\ComercialController;
use App\Http\Controllers\Financeiro\FinanceiroController;
use App\Http\Controllers\Central\CentralController;

Route::get('/', [HubController::class, 'index'])->name('hub.home');

Route::prefix('comercial')->name('comercial.')->group(function () {
    Route::get('/', [ComercialController::class, 'index'])->name('home');
});

Route::prefix('financeiro')->name('financeiro.')->group(function () {
    Route::get('/', [FinanceiroController::class, 'index'])->name('home');
});

Route::prefix('central')->name('central.')->group(function () {
    Route::get('/', [CentralController::class, 'index'])->name('home');
});
