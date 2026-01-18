<?php

use App\Support\Context\PortalContext;
use Illuminate\Support\Facades\Route;

Route::prefix('amazing')
    ->as('amazing.')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::get('/', function (PortalContext $portal) {
            $portal->set('amazing');

            $scope = (string) config('amazing.default_scope', 'default');
            return redirect()->route('hub.index', ['scope' => $scope]);
        })->name('home');
    });
