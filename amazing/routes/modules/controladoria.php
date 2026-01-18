<?php

use App\Http\Controllers\Controladoria\StoreController;
use App\Http\Controllers\Controladoria\UserAccessController;
use Illuminate\Support\Facades\Route;

Route::prefix('controladoria')
    ->as('controladoria.')
    ->group(function () {
        Route::get('/', function () {
            return view('modules.controladoria.index');
        })->name('index');

        // Stores
        Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
        Route::get('/stores/create', [StoreController::class, 'create'])->name('stores.create');
        Route::post('/stores', [StoreController::class, 'store'])->name('stores.store');

        // Users + Acessos (Etapa 4)
        Route::get('/users', [UserAccessController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/access', [UserAccessController::class, 'edit'])->name('users.access.edit');
        Route::post('/users/{user}/access', [UserAccessController::class, 'update'])->name('users.access.update');
    });
