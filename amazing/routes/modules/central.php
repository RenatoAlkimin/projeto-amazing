<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Central\CentralController;

Route::prefix('central')->name('central.')->group(function () {
    Route::get('/', [CentralController::class, 'index'])->name('home');
});
