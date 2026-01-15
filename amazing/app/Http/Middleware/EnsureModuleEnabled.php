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
        private PortalContext $portal,
        private ScopeContext $scopeCtx,
        private TenantModules $tenantModules,
    ) {}

    public function handle(Request $request, Closure $next, string $module)
    {
        abort_unless(array_key_exists($module, (array) config('modules', [])), 404, "Módulo desconhecido.");

        $portal = $this->portal->get();
        $scope = $this->scopeCtx->current();

        abort_unless(
            $this->portal->allows($module, $portal),
            403,
            "Módulo '{$module}' não disponível para o portal '{$portal}'."
        );

        // ✅ Superadmin (por enquanto): painel "amazing" ignora entitlements do tenant (scope)
        if ($portal === 'amazing') {
            return $next($request);
        }

        abort_unless(
            $this->tenantModules->allows($scope, $module),
            403,
            "Módulo '{$module}' não contratado para a loja '{$scope}'."
        );

        return $next($request);
    }
}
