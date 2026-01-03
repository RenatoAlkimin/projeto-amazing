<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureModuleEnabled
{
    public function handle(Request $request, Closure $next, string $module)
    {
        $portal = app()->bound('currentPortal')
            ? app('currentPortal')
            : $request->session()->get('portal', 'loja');

        $allowed = config("portals.$portal.modules", []);
        $ok = in_array('*', $allowed, true) || in_array($module, $allowed, true);

        abort_unless($ok, 403, "Módulo '{$module}' não disponível para o portal '{$portal}'.");

        return $next($request);
    }
}
