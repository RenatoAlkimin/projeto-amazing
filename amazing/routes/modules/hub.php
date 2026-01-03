<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HubController;

/**
 * HUB (root do scope)
 * URL final: /s/{scope}
 * Nome da rota: hub.index
 */
Route::get('/', [HubController::class, 'index'])->name('hub.index');
