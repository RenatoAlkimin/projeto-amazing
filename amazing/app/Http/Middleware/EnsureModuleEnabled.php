<?php

namespace App\Http\Middleware;

use App\Support\Context\PortalContext;
use Closure;
use Illuminate\Http\Request;

class EnsureModuleEnabled
{
    public function __construct(private PortalContext $portal)
    {
    }

    public function handle(Request $request, Closure $next, string $module)
    {
        $portal = $this->portal->get();

        abort_unless(
            $this->portal->allows($module, $portal),
            403,
            "Módulo '{$module}' não disponível para o portal '{$portal}'."
        );

        return $next($request);
    }
}
