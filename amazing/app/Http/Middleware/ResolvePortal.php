<?php

namespace App\Http\Middleware;

use App\Support\Context\PortalContext;
use Closure;
use Illuminate\Http\Request;

class ResolvePortal
{
    public function __construct(private PortalContext $portal)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $available = $this->portal->availablePortals();

        // DEV-ONLY switch via query param (controle pra não vazar pra prod)
        $fromQuery = $request->query('portal');
        $allowQuerySwitch =
            app()->environment('local')
            && (bool) config('amazing.allow_portal_query_switch', false);

        if ($allowQuerySwitch && $fromQuery && in_array($fromQuery, $available, true)) {
            $this->portal->set($fromQuery);
            return $next($request);
        }

        // Sessão / fallback
        $current = (string) $request->session()->get('portal', $this->portal->defaultPortal());
        $this->portal->set($current);

        return $next($request);
    }
}
