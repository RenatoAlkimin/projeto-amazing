<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortalModuleAccessTest extends TestCase
{
    public function test_each_portal_can_access_only_its_allowed_modules(): void
    {
        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        // Para esse teste aqui ser "só portal", garantimos que o tenant não bloqueie nada no default scope.
        config()->set('tenants.default.modules', ['*']);

        $defaultScope = (string) config('amazing.default_scope', 'default');
        $portals = (array) config('portals', []);
        $modules = (array) config('modules', []);

        foreach ($portals as $portalId => $portalConfig) {
            $allowed = (array) ($portalConfig['modules'] ?? []);
            $wildcard = in_array('*', $allowed, true);

            foreach ($modules as $moduleKey => $moduleConfig) {
                $routeName = (string) ($moduleConfig['route'] ?? '');
                if ($routeName === '') {
                    continue;
                }

                $url = route($routeName, ['scope' => $defaultScope]);
                $url .= (str_contains($url, '?') ? '&' : '?') . 'portal=' . $portalId;

                $response = $this->get($url);

                $isAllowed = $wildcard || in_array($moduleKey, $allowed, true);

                if ($isAllowed) {
                    $response->assertStatus(200);
                } else {
                    $response->assertStatus(403);
                }
            }
        }
    }

    public function test_diagnostics_is_404_when_disabled(): void
    {
        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', false);

        $defaultScope = (string) config('amazing.default_scope', 'default');

        $url = route('diagnostics.index', ['scope' => $defaultScope]) . '?portal=amazing';

        $this->get($url)->assertStatus(404);
    }

    public function test_sidebar_shows_diagnostics_only_on_amazing_portal(): void
    {
        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        // Sidebar depende de tenant também (exceto amazing), então garantimos default liberado aqui.
        config()->set('tenants.default.modules', ['*']);

        $this->get('/s/default?portal=amazing')
            ->assertSee('Diagnostics');

        $this->get('/s/default?portal=loja')
            ->assertDontSee('Diagnostics');
    }

    public function test_amazing_ignores_tenant_entitlements_on_route_access(): void
    {
        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        // Scope com contrato restrito (sem o módulo que vamos testar)
        config()->set('tenants.loja_002.modules', ['rh']);

        $modules = (array) config('modules', []);
        $amazingAllowed = (array) data_get(config('portals'), 'amazing.modules', []);
        $franchisingAllowed = (array) data_get(config('portals'), 'franchising.modules', []);

        // Escolhe um módulo com rota que:
        // - exista no catálogo
        // - esteja permitido em amazing e franchising
        // - NÃO esteja no contrato de loja_002 (['rh'])
        $candidateKeys = array_keys($modules);
        $amazingSet = in_array('*', $amazingAllowed, true) ? $candidateKeys : $amazingAllowed;
        $franchisingSet = in_array('*', $franchisingAllowed, true) ? $candidateKeys : $franchisingAllowed;

        $intersection = array_values(array_intersect($amazingSet, $franchisingSet));

        $moduleKey = null;
        $routeName = null;

        foreach ($intersection as $key) {
            if ($key === 'rh') {
                continue;
            }
            $conf = $modules[$key] ?? null;
            if (!is_array($conf)) {
                continue;
            }
            $r = (string) ($conf['route'] ?? '');
            if ($r === '') {
                continue;
            }

            $moduleKey = $key;
            $routeName = $r;
            break;
        }

        $this->assertNotNull($moduleKey, 'Não foi possível encontrar um módulo candidato para o teste.');
        $this->assertNotNull($routeName, 'Não foi possível encontrar uma rota para o módulo candidato.');

        $url = route($routeName, ['scope' => 'loja_002']);

        // amazing deve passar (bypass entitlements)
        $this->get($url . '?portal=amazing')->assertStatus(200);

        // franchising deve ser bloqueado pelo tenant (entitlements)
        $this->get($url . '?portal=franchising')->assertStatus(403);
    }

    public function test_amazing_sidebar_ignores_tenant_entitlements(): void
    {
        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        config()->set('tenants.loja_002.modules', ['rh']);

        $modules = (array) config('modules', []);
        $amazingAllowed = (array) data_get(config('portals'), 'amazing.modules', []);
        $franchisingAllowed = (array) data_get(config('portals'), 'franchising.modules', []);

        $candidateKeys = array_keys($modules);
        $amazingSet = in_array('*', $amazingAllowed, true) ? $candidateKeys : $amazingAllowed;
        $franchisingSet = in_array('*', $franchisingAllowed, true) ? $candidateKeys : $franchisingAllowed;

        $intersection = array_values(array_intersect($amazingSet, $franchisingSet));

        $label = null;

        foreach ($intersection as $key) {
            if ($key === 'rh') {
                continue;
            }
            $conf = $modules[$key] ?? null;
            if (!is_array($conf)) {
                continue;
            }
            $r = (string) ($conf['route'] ?? '');
            if ($r === '') {
                continue;
            }

            $label = (string) ($conf['label'] ?? $key);
            break;
        }

        $this->assertNotNull($label, 'Não foi possível encontrar um módulo candidato para o teste de sidebar.');

        // amazing deve mostrar o módulo (bypass entitlements)
        $this->get('/s/loja_002?portal=amazing')
            ->assertSee($label);

        // franchising não deve mostrar (entitlements bloqueia)
        $this->get('/s/loja_002?portal=franchising')
            ->assertDontSee($label);
    }
}
