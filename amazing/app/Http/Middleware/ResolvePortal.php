<?php

namespace App\Http\Middleware;

use App\Support\Context\PortalContext;
use Closure;
use Illuminate\Http\Request;

class ResolvePortal
{
    public function __construct(private PortalContext $portal) {}

    public function handle(Request $request, Closure $next)
    {
        $available = $this->portal->availablePortals();

        $fromQuery = (string) $request->query('portal', '');

        $allowQuerySwitch =
            app()->environment(['local', 'testing'])
            && (bool) config('amazing.allow_portal_query_switch', false);

        // DEV/TEST-only: troca via query param
        if ($allowQuerySwitch && $fromQuery !== '' && in_array($fromQuery, $available, true)) {
            $this->portal->set($fromQuery);
            return $next($request);
        }

        // Normaliza: garante que o portal atual é válido e fica persistido em sessão
        $this->portal->set($this->portal->currentId());

        return $next($request);
    }
}
