<?php

namespace App\Support\Context;

class PortalContext
{
    /**
     * Chave usada pelo middleware ResolvePortal
     */
    public const SESSION_KEY = 'portal';

    /**
     * (Opcional) compatibilidade caso algo antigo use essa key.
     */
    public const LEGACY_SESSION_KEY = 'amazing.portal';

    /**
     * @return string[]
     */
    public function availablePortals(): array
    {
        return array_keys((array) config('portals', []));
    }

    public function defaultPortal(): string
    {
        $portals = (array) config('portals', []);

        // controle por config
        $configured = (string) config('amazing.default_portal', '');
        if ($configured !== '' && array_key_exists($configured, $portals)) {
            return $configured;
        }

        // ✅ fallback seguro: tenta "loja" antes do first
        if (array_key_exists('loja', $portals)) {
            return 'loja';
        }

        return array_key_first($portals) ?? 'loja';
    }

    public function set(string $portalId): void
    {
        if (!$this->exists($portalId)) {
            $portalId = $this->defaultPortal();
        }

        session()->put(self::SESSION_KEY, $portalId);
        session()->put(self::LEGACY_SESSION_KEY, $portalId); // compat
    }

    public function currentId(): string
    {
        $portals = (array) config('portals', []);

        $portal = (string) session()->get(self::SESSION_KEY, '');
        if ($portal !== '' && array_key_exists($portal, $portals)) {
            return $portal;
        }

        // compat (caso algo tenha setado a legacy key)
        $legacy = (string) session()->get(self::LEGACY_SESSION_KEY, '');
        if ($legacy !== '' && array_key_exists($legacy, $portals)) {
            // migra pro padrão
            session()->put(self::SESSION_KEY, $legacy);
            return $legacy;
        }

        return $this->defaultPortal();
    }

    /**
     * Alias pra compat se algum lugar usar ->get()
     */
    public function get(): string
    {
        return $this->currentId();
    }

    public function currentConfig(): array
    {
        return (array) config('portals.' . $this->currentId(), []);
    }

    public function label(): string
    {
        return (string) ($this->currentConfig()['label'] ?? $this->currentId());
    }

    /**
     * @return string[]
     */
    public function allowedModules(): array
    {
        $portalModules = (array) ($this->currentConfig()['modules'] ?? []);
        $catalog = (array) config('modules', []);

        if (in_array('*', $portalModules, true)) {
            return array_keys($catalog);
        }

        $allowed = [];
        foreach ($portalModules as $m) {
            $m = (string) $m;
            if ($m !== '' && array_key_exists($m, $catalog)) {
                $allowed[] = $m;
            }
        }

        return array_values(array_unique($allowed));
    }

    public function allows(string $module, ?string $portalId = null): bool
    {
        $portalId = $portalId ?: $this->currentId();

        $portals = (array) config('portals', []);
        if (!array_key_exists($portalId, $portals)) {
            $portalId = $this->defaultPortal();
        }

        // Se o módulo nem existe no catálogo, não libera.
        $catalog = (array) config('modules', []);
        if (!array_key_exists($module, $catalog)) {
            return false;
        }

        $modules = (array) config("portals.{$portalId}.modules", []);

        // Wildcard: acesso total
        if (in_array('*', $modules, true)) {
            return true;
        }

        return in_array($module, $modules, true);
    }

    private function exists(string $portalId): bool
    {
        $portals = (array) config('portals', []);
        return $portalId !== '' && array_key_exists($portalId, $portals);
    }
}
