<?php

use App\Support\Context\PortalContext;
use Illuminate\Support\Facades\Route;

Route::prefix('loja')
    ->as('loja.')
    ->middleware(['web']) // depois: auth
    ->group(function () {
        Route::get('/', function (PortalContext $portal) {
            $portal->set('loja');

            $scope = (string) config('amazing.default_scope', 'default');
            return redirect()->route('hub.index', ['scope' => $scope]);
        })->name('home');
    });
