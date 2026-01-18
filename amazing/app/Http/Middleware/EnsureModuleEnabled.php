<?php

namespace App\Http\Middleware;

use App\Support\Access\TenantModules;
use App\Support\Context\PortalContext;
use App\Support\Context\ScopeContext;
use Closure;
use Illuminate\Http\Request;

class EnsureModuleEnabled
{
    public function __construct(
        private readonly PortalContext $portal,
        private readonly ScopeContext $scopeCtx,
        private readonly TenantModules $tenantModules,
    ) {}

    public function handle(Request $request, Closure $next, string $module)
    {
        $portalId = (string) $this->portal->get();
        $scope = (string) $this->scopeCtx->current();

        // Feature flag: diagnostics pode ser desligado (vira 404 mesmo no Amazing)
        if ($module === 'diagnostics' && ! (bool) config('amazing.enable_diagnostics', false)) {
            abort(404);
        }

        /**
         * 1) Regra do PORTAL (amazing vs vaapty)
         * - Vaapty só pode acessar os módulos listados em config/portals.php
         * - Amazing normalmente tem '*' (catálogo inteiro)
         */
        abort_unless(
            $this->portal->allows($module),
            403,
            "Módulo '{$module}' não permitido no portal '{$portalId}'."
        );

        /**
         * 2) Regra do TENANT (módulos contratados por loja)
         * - Amazing ignora contrato (superadmin / painel interno)
         * - Vaapty respeita contrato do scope
         */
        if ($portalId !== 'amazing') {
            abort_unless(
                $this->tenantModules->allows($scope, $module),
                403,
                "Módulo '{$module}' não contratado para a loja '{$scope}'."
            );
        }

        return $next($request);
    }
}
