<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalModuleAccessTest extends TestCase
{
    use RefreshDatabase;

    private function bootTestContext(): void
    {
        $defaultStore = Store::query()->create([
            'scope_slug' => 'default',
            'name' => 'Default',
            'status' => 'active',
        ]);

        $loja002 = Store::query()->create([
            'scope_slug' => 'loja_002',
            'name' => 'Loja 002',
            'status' => 'active',
        ]);

        // Role mínima pro membership (Etapa 4)
        $admRole = Role::query()->create([
            'key' => 'adm',
            'name' => 'Admin',
            'scope_type' => 'store',
            'level' => 100,
        ]);

        $user = User::factory()->create([
            'username' => 'tester',
        ]);

        // IMPORTANTÍSSIMO:
        // Com ensure_scope_access ativo, VAAPTY só entra no /s/{scope}
        // se tiver membership store_user.
        $user->stores()->attach($defaultStore->id, [
            'role_id' => $admRole->id,
            'status' => 'active',
        ]);

        $user->stores()->attach($loja002->id, [
            'role_id' => $admRole->id,
            'status' => 'active',
        ]);

        $this->actingAs($user);
    }

    public function test_each_portal_can_access_only_its_allowed_modules(): void
    {
        $this->bootTestContext();

        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        // Para esse teste ser "só portal", garantimos que o tenant não bloqueie nada no default scope.
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

                $isAllowed ? $response->assertStatus(200) : $response->assertStatus(403);
            }
        }
    }

    public function test_diagnostics_is_404_when_disabled(): void
    {
        $this->bootTestContext();

        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', false);

        $defaultScope = (string) config('amazing.default_scope', 'default');

        $url = route('diagnostics.index', ['scope' => $defaultScope]) . '?portal=amazing';

        $this->get($url)->assertStatus(404);
    }

    public function test_sidebar_shows_diagnostics_only_on_amazing_portal(): void
    {
        $this->bootTestContext();

        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        // Sidebar depende de tenant também (exceto amazing),
        // então garantimos default liberado aqui.
        config()->set('tenants.default.modules', ['*']);

        $this->get('/s/default?portal=amazing')->assertSee('Diagnostics');
        $this->get('/s/default?portal=vaapty')->assertDontSee('Diagnostics');
    }

    public function test_amazing_ignores_tenant_entitlements_on_route_access(): void
    {
        $this->bootTestContext();

        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        // Scope com contrato restrito (sem o módulo que vamos testar)
        config()->set('tenants.loja_002.modules', ['rh']);

        $modules = (array) config('modules', []);
        $amazingAllowed = (array) data_get(config('portals'), 'amazing.modules', []);
        $vaaptyAllowed  = (array) data_get(config('portals'), 'vaapty.modules', []);

        $candidateKeys = array_keys($modules);
        $amazingSet = in_array('*', $amazingAllowed, true) ? $candidateKeys : $amazingAllowed;
        $vaaptySet  = in_array('*', $vaaptyAllowed, true) ? $candidateKeys : $vaaptyAllowed;

        $intersection = array_values(array_intersect($amazingSet, $vaaptySet));

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

        $this->assertNotNull($moduleKey);
        $this->assertNotNull($routeName);

        $url = route($routeName, ['scope' => 'loja_002']);

        // amazing deve passar (bypass entitlements)
        $this->get($url . '?portal=amazing')->assertStatus(200);

        // vaapty deve ser bloqueado pelo tenant (entitlements)
        $this->get($url . '?portal=vaapty')->assertStatus(403);
    }

    public function test_amazing_sidebar_ignores_tenant_entitlements(): void
    {
        $this->bootTestContext();

        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

        config()->set('tenants.loja_002.modules', ['rh']);

        $modules = (array) config('modules', []);
        $amazingAllowed = (array) data_get(config('portals'), 'amazing.modules', []);
        $vaaptyAllowed  = (array) data_get(config('portals'), 'vaapty.modules', []);

        $candidateKeys = array_keys($modules);
        $amazingSet = in_array('*', $amazingAllowed, true) ? $candidateKeys : $amazingAllowed;
        $vaaptySet  = in_array('*', $vaaptyAllowed, true) ? $candidateKeys : $vaaptyAllowed;

        $intersection = array_values(array_intersect($amazingSet, $vaaptySet));

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

        $this->assertNotNull($label);

        $this->get('/s/loja_002?portal=amazing')->assertSee($label);
        $this->get('/s/loja_002?portal=vaapty')->assertDontSee($label);
    }
}
