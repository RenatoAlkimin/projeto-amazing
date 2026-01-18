<?php

namespace App\Http\Middleware;

use App\Models\Store;
use App\Support\Context\PortalContext;
use Closure;
use Illuminate\Http\Request;

class EnsureScopeAccess
{
    public function handle(Request $request, Closure $next)
    {
        $portal = app(PortalContext::class)->get();

        // Amazing é superadmin: bypass total
        if ($portal === 'amazing') {
            return $next($request);
        }

        $user = $request->user();
        if (! $user) {
            // Não deveria acontecer (já tem auth), mas fica safe
            return redirect()->route('login');
        }

        $scope = (string) $request->route('scope', '');
        if ($scope === '') {
            return $next($request);
        }

        // ensure_store_exists já garante existência, mas aqui precisamos do ID
        $store = Store::query()->where('scope_slug', $scope)->first();
        if (! $store) {
            abort(404, 'Loja não encontrada.');
        }

        $hasAccess = $user->stores()
            ->where('stores.id', $store->id)
            ->wherePivot('status', 'active')
            ->exists();

        if ($hasAccess) {
            return $next($request);
        }

        // Em testes / API: 403 direto (evita redirect quebrar asserts)
        if ($request->expectsJson() || app()->environment('testing')) {
            abort(403, 'Sem acesso a esta loja.');
        }

        // UX: se tiver alguma loja permitida, redireciona pra primeira
        $first = $user->stores()
            ->wherePivot('status', 'active')
            ->orderBy('stores.scope_slug')
            ->first();

        if ($first) {
            return redirect()
                ->route('hub.index', ['scope' => $first->scope_slug])
                ->with('error', 'Você não tem acesso a esta loja. Redirecionado para uma loja permitida.');
        }

        abort(403, 'Sem acesso a nenhuma loja.');
    }
}
