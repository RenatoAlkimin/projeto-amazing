<?php

namespace App\Support\Context;

class PortalContext
{
    public function availablePortals(): array
    {
        return array_keys(config('portals', []));
    }

    public function defaultPortal(): string
    {
        return (string) config('amazing.default_portal', 'loja');
    }

    public function get(): string
    {
        if (app()->bound('currentPortal')) {
            return (string) app('currentPortal');
        }

        $portal = (string) session('portal', $this->defaultPortal());

        return $this->isValid($portal) ? $portal : $this->defaultPortal();
    }

    public function set(string $portal): string
    {
        if (!$this->isValid($portal)) {
            $portal = $this->defaultPortal();
        }

        session()->put('portal', $portal);
        app()->instance('currentPortal', $portal);
        view()->share('currentPortal', $portal);

        return $portal;
    }

    public function allowedModules(?string $portal = null): array
    {
        $portal ??= $this->get();
        $allowed = config("portals.$portal.modules", []);

        return is_array($allowed) ? $allowed : [];
    }

    public function allows(string $module, ?string $portal = null): bool
    {
        $allowed = $this->allowedModules($portal);

        return in_array('*', $allowed, true) || in_array($module, $allowed, true);
    }

    public function homeRoute(?string $portal = null): ?string
    {
        $portal ??= $this->get();
        $home = config("portals.$portal.home_route");

        return is_string($home) && $home !== '' ? $home : null;
    }

    private function isValid(string $portal): bool
    {
        return in_array($portal, $this->availablePortals(), true);
    }
}
