<?php

use App\Support\Context\PortalContext;
use Illuminate\Support\Facades\Route;

Route::prefix('vaapty')
    ->as('vaapty.')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::get('/', function (PortalContext $portal) {
            $portal->set('vaapty');

            $scope = (string) config('amazing.default_scope', 'default');
            return redirect()->route('hub.index', ['scope' => $scope]);
        })->name('home');
    });