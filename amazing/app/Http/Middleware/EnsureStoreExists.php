<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;

class EnsureStoreExists
{
    public function handle(Request $request, Closure $next)
    {
        $scope = (string) $request->route('scope', '');

        // Se não veio scope na rota, deixa seguir (não é rota /s/{scope})
        if ($scope === '') {
            return $next($request);
        }

        $exists = Store::query()->where('scope_slug', $scope)->exists();

        if (! $exists) {
            abort(404, 'Loja não encontrada.');
        }

        return $next($request);
    }
}
