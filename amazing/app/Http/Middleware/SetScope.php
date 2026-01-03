<?php

namespace App\Http\Middleware;

use App\Support\Context\ScopeContext;
use Closure;
use Illuminate\Http\Request;

class SetScope
{
    public function __construct(private ScopeContext $scopeCtx)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $scope = (string) $request->route('scope', '');

        if ($scope !== '' && !$this->scopeCtx->isValid($scope)) {
            abort(400, 'Scope inválido.');
        }

        // Se veio na rota, vira o atual. Se não veio, usa o que já está em sessão/default.
        if ($scope !== '') {
            $this->scopeCtx->set($scope);
        } else {
            $this->scopeCtx->set($this->scopeCtx->get());
        }

        return $next($request);
    }
}
