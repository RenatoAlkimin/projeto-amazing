<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HubController;

Route::get('/', [HubController::class, 'index'])->name('hub.home');
