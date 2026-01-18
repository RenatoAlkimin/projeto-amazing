<?php

namespace App\Http\Middleware;

use App\Support\Context\PortalContext;
use Closure;
use Illuminate\Http\Request;

class EnsurePortalAccess
{
    public function handle(Request $request, Closure $next)
    {
        $portal = app(PortalContext::class)->get();
        $user = $request->user();

        if ($portal !== 'amazing') {
            return $next($request);
        }

        if ($user && method_exists($user, 'isSuperadmin') && $user->isSuperadmin()) {
            return $next($request);
        }

        if ($request->expectsJson() || app()->environment('testing')) {
            abort(403, 'Acesso negado ao painel Amazing.');
        }

        return redirect('/vaapty')->with('error', 'Acesso negado ao painel Amazing.');
    }
}
