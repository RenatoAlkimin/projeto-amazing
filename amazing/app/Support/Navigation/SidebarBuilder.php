<?php

namespace App\Support\Navigation;

use App\Support\Access\TenantModules;
use App\Support\Context\PortalContext;
use App\Support\Context\ScopeContext;
use Illuminate\Http\Request;

class SidebarBuilder
{
    public function __construct(
        private readonly PortalContext $portal,
        private readonly ScopeContext $scope,
        private readonly TenantModules $tenantModules,
        private readonly Request $request,
    ) {}

    /**
     * @return array<string, array{label:string, items: array<int, array{key:string,label:string,url:string,icon:?string,active:bool}>}>
     */
    public function build(): array
    {
        $catalog = (array) config('modules', []);
        $scope = $this->scope->current();

        $portalId = $this->portal->get();

        $portalAllowed = $this->portal->allowedModules();

        // ✅ Superadmin (por enquanto): painel "amazing" ignora entitlements do tenant (scope)
        if ($portalId === 'amazing') {
            $allowed = $portalAllowed;
        } else {
            $tenantAllowed = $this->tenantModules->allowedModules($scope);

            // Interseção: portal ∩ tenant
            $allowed = array_values(array_intersect($portalAllowed, $tenantAllowed));
        }

        $items = [];

        foreach ($allowed as $key) {
            $m = $catalog[$key] ?? null;
            if (!is_array($m)) {
                continue;
            }

            $routeName = (string) ($m['route'] ?? '');
            if ($routeName === '') {
                continue;
            }

            $items[] = [
                'key' => $key,
                'label' => (string) ($m['label'] ?? $key),
                'icon' => $m['icon'] ?? null,
                'section' => (string) ($m['section'] ?? 'principal'),
                'order' => (int) ($m['order'] ?? 999),
                'url' => route($routeName, ['scope' => $scope]),
                'active' => $this->request->routeIs($key . '.*') || $this->request->routeIs($routeName),
            ];
        }

        usort(
            $items,
            fn ($a, $b) => [$a['section'], $a['order'], $a['label']] <=> [$b['section'], $b['order'], $b['label']]
        );

        $sections = [];
        foreach ($items as $it) {
            $secKey = $it['section'];

            if (!isset($sections[$secKey])) {
                $sections[$secKey] = [
                    'label' => $this->sectionLabel($secKey),
                    'items' => [],
                ];
            }

            $sections[$secKey]['items'][] = [
                'key' => $it['key'],
                'label' => $it['label'],
                'url' => $it['url'],
                'icon' => $it['icon'],
                'active' => (bool) $it['active'],
            ];
        }

        return $sections;
    }

    private function sectionLabel(string $key): string
    {
        return match ($key) {
            'principal' => 'Principal',
            'admin' => 'Admin',
            default => ucfirst($key),
        };
    }
}
