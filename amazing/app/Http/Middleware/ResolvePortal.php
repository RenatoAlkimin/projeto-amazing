<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResolvePortal
{
    public function handle(Request $request, Closure $next)
    {
        $available = array_keys(config('portals', []));

        // 1) Se vier ?portal=... (bom pra testar), salva na sessão
        $fromQuery = $request->query('portal');
        if ($fromQuery && in_array($fromQuery, $available, true)) {
            $request->session()->put('portal', $fromQuery);
        }

        // 2) Tenta sessão
        $portal = $request->session()->get('portal');

        // 3) Fallback (por enquanto)
        if (!$portal || !in_array($portal, $available, true)) {
            $portal = 'loja';
            $request->session()->put('portal', $portal);
        }

        // Disponibiliza globalmente
        app()->instance('currentPortal', $portal);

        // Disponibiliza nas views (sidebar etc.)
        view()->share('currentPortal', $portal);

        return $next($request);
    }
}
