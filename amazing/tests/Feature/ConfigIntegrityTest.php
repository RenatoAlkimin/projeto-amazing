<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ConfigIntegrityTest extends TestCase
{
    public function test_portals_reference_existing_modules(): void
    {
        $modules = array_keys(config('modules', []));
        $portals = config('portals', []);

        foreach ($portals as $portalKey => $portal) {
            $allowed = $portal['modules'] ?? [];
            $this->assertIsArray($allowed, "portals.$portalKey.modules deve ser array");

            foreach ($allowed as $m) {
                if ($m === '*') continue;

                $this->assertContains(
                    $m,
                    $modules,
                    "Portal '$portalKey' referencia modulo '$m' que não existe em config/modules.php"
                );
            }
        }
    }

    public function test_modules_have_routes_registered(): void
    {
        $modules = config('modules', []);

        foreach ($modules as $key => $mod) {
            $route = $mod['route'] ?? null;
            $this->assertIsString($route, "modules.$key.route deve existir e ser string");
            $this->assertTrue(Route::has($route), "Rota '$route' do módulo '$key' não está registrada");
        }
    }
}
