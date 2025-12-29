<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Comercial\ComercialController;

Route::prefix('comercial')->name('comercial.')->group(function () {
    Route::get('/', [ComercialController::class, 'index'])->name('home');
});
