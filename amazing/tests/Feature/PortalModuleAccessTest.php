<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortalModuleAccessTest extends TestCase
{
    public function test_each_portal_can_access_only_its_allowed_modules(): void
    {
        config()->set('amazing.allow_portal_query_switch', true);
        config()->set('amazing.enable_diagnostics', true);

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

    $this->get('/s/default?portal=amazing')
        ->assertSee('Diagnostics');

    $this->get('/s/default?portal=loja')
        ->assertDontSee('Diagnostics');
}

     
}
