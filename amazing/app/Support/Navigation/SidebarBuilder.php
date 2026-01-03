<?php

namespace App\Support\Navigation;

use App\Support\Context\PortalContext;
use App\Support\Context\ScopeContext;
use Illuminate\Support\Facades\Route;

class SidebarBuilder
{
    public function __construct(
        private PortalContext $portal,
        private ScopeContext $scope,
    ) {}

    public function build(): array
    {
        $portal = $this->portal->get();
        $scope  = $this->scope->get();

        $modules = config('modules', []);

        $sectionWeight = [
            'principal' => 10,
            'admin'     => 20,
        ];

        uasort($modules, function ($a, $b) use ($sectionWeight) {
            $sa = $sectionWeight[$a['section'] ?? 'principal'] ?? 999;
            $sb = $sectionWeight[$b['section'] ?? 'principal'] ?? 999;

            return [$sa, $a['order'] ?? 999] <=> [$sb, $b['order'] ?? 999];
        });

        $items = [];

        foreach ($modules as $key => $mod) {
            if (!$this->portal->allows($key, $portal)) {
                continue;
            }

            $permission = $mod['permission'] ?? null;
            if ($permission && auth()->check() && !auth()->user()->can($permission)) {
                continue;
            }

            $routeName = $mod['route'] ?? null;
            if (!$routeName || !Route::has($routeName)) {
                continue;
            }

            $items[] = [
                'key'     => $key,
                'label'   => $mod['label'] ?? ucfirst($key),
                'href'    => route($routeName, ['scope' => $scope]),
                'active'  => request()->routeIs($key . '.*'),
                'section' => $mod['section'] ?? 'principal',
                'icon'    => $mod['icon'] ?? null,
            ];
        }

        return $items;
    }
}
