<?php

namespace App\Support\Navigation;

use Illuminate\Support\Facades\Route;

class SidebarBuilder
{
    public function build(): array
    {
        $portal = $this->currentPortal();
        $scope  = $this->currentScope();

        $portalModules = config("portals.$portal.modules", []);
        $modules = config('modules', []);

        // ordena por "order" se existir
        uasort($modules, fn ($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));

        $items = [];

        foreach ($modules as $key => $mod) {
            if (!$this->portalAllows($portalModules, $key)) {
                continue;
            }

            // Permissões finas (deixa pronto; hoje sem auth pode ignorar)
            $permission = $mod['permission'] ?? null;
            if ($permission && auth()->check() && !auth()->user()->can($permission)) {
                continue;
            }

            $routeName = $mod['route'] ?? null;
            if (!$routeName || !Route::has($routeName)) {
                // Evita quebrar a página se alguém esquecer de registrar rota
                continue;
            }

            $href = route($routeName, ['scope' => $scope]);
            $active = request()->routeIs($key . '.*');

            $items[] = [
                'key' => $key,
                'label' => $mod['label'] ?? ucfirst($key),
                'href' => $href,
                'active' => $active,
                'section' => $mod['section'] ?? 'principal',
                'icon' => $mod['icon'] ?? null, // opcional
            ];
        }

        return $items;
    }

    private function portalAllows(array $portalModules, string $moduleKey): bool
    {
        return in_array('*', $portalModules, true) || in_array($moduleKey, $portalModules, true);
    }

    private function currentPortal(): string
    {
        if (app()->bound('currentPortal')) {
            return (string) app('currentPortal');
        }

        return (string) session('portal', 'loja');
    }

    private function currentScope(): string
    {
        if (app()->bound('currentScope')) {
            return (string) app('currentScope');
        }

        return (string) session('scope', 'default');
    }
}
