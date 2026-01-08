<?php

namespace App\Http\Controllers\Diagnostics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DiagnosticsController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Dev-only: se não estiver habilitado, some do mapa (404)
        abort_unless((bool) config('amazing.enable_diagnostics', false), 404);

        $scope = (string) ($request->route('scope') ?? config('amazing.default_scope', 'default'));

        // Portal atual (ajuste se teu ResolvePortal usar outra chave)
        $portalId = (string) (session('portal') ?? $request->query('portal') ?? 'unknown');

        $manifestPath = public_path('build/manifest.json');
        $hotPath = public_path('hot');

        $modules = (array) config('modules', []);
        $portals = (array) config('portals', []);

        $catalogKeys = array_keys($modules);

        // Mapa de portais -> módulos efetivamente permitidos (resolve wildcard '*')
        $portalMatrix = [];
        foreach ($portals as $pid => $pconf) {
            $allowed = (array) ($pconf['modules'] ?? []);
            $effective = in_array('*', $allowed, true) ? $catalogKeys : $allowed;

            // Normaliza: remove módulos inválidos e reindexa
            $effective = array_values(array_intersect($effective, $catalogKeys));

            $portalMatrix[$pid] = [
                'label' => (string) ($pconf['label'] ?? $pid),
                'modules' => $effective,
            ];
        }

        // Status das rotas por módulo
        $moduleRoutes = [];
        foreach ($modules as $key => $mconf) {
            $routeName = (string) ($mconf['route'] ?? '');
            $has = $routeName !== '' && Route::has($routeName);

            $moduleRoutes[$key] = [
                'label' => (string) ($mconf['label'] ?? $key),
                'route' => $routeName,
                'route_exists' => $has,
                'url' => $has ? route($routeName, ['scope' => $scope]) : null,
            ];
        }

        return view('modules.diagnostics.index', [
            'scope' => $scope,
            'portalId' => $portalId,
            'portalLabel' => (string) data_get($portals, "{$portalId}.label", $portalId),

            'vite' => [
                'manifest_path' => $manifestPath,
                'manifest_exists' => is_file($manifestPath),
                'hot_path' => $hotPath,
                'hot_exists' => is_file($hotPath),
            ],

            'portalMatrix' => $portalMatrix,
            'moduleRoutes' => $moduleRoutes,
        ]);
    }
}
