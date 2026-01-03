<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetScope
{
    public function handle(Request $request, Closure $next)
    {
        // rota: /s/{scope}/...
        $scope = (string) $request->route('scope', '');

        // Validação básica (depois você troca por validação real via OrgUnits)
        if ($scope !== '' && !preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $scope)) {
            abort(400, 'Scope inválido.');
        }

        // Se não veio scope na rota, tenta sessão (útil pra futuras telas)
        if ($scope === '') {
            $scope = (string) $request->session()->get('scope', 'default');
        } else {
            $request->session()->put('scope', $scope);
        }

        app()->instance('currentScope', $scope);
        view()->share('currentScope', $scope);

        return $next($request);
    }
}
