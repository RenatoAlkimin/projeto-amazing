<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ConfigIntegrityTest extends TestCase
{
    public function test_portals_reference_existing_modules(): void
    {
        $portals = (array) config('portals', []);
        $catalog = array_keys((array) config('modules', []));

        $this->assertNotEmpty($portals, 'Config de portais está vazia (config/portals.php).');
        $this->assertNotEmpty($catalog, 'Config de módulos está vazia (config/modules.php).');

        foreach ($portals as $portalId => $portalConfig) {
            $modules = (array) ($portalConfig['modules'] ?? []);

            // Wildcard: portal pode acessar todos os módulos do catálogo
            if (in_array('*', $modules, true)) {
                continue;
            }

            foreach ($modules as $module) {
                $this->assertContains(
                    $module,
                    $catalog,
                    "Portal '{$portalId}' referencia módulo inexistente: '{$module}'."
                );
            }
        }
    }

    public function test_each_configured_module_has_route_and_route_is_registered(): void
    {
        $modules = (array) config('modules', []);
        $this->assertNotEmpty($modules, 'Config de módulos está vazia (config/modules.php).');

        foreach ($modules as $key => $moduleConfig) {
            $routeName = (string) ($moduleConfig['route'] ?? '');

            $this->assertNotSame('', $routeName, "Módulo '{$key}' não define a key 'route'.");

            $this->assertTrue(
                Route::has($routeName),
                "Módulo '{$key}' aponta para rota '{$routeName}', mas ela não está registrada."
            );
        }
    }

    public function test_each_configured_module_has_routes_file(): void
    {
        $modules = array_keys((array) config('modules', []));
        $this->assertNotEmpty($modules, 'Config de módulos está vazia (config/modules.php).');

        foreach ($modules as $module) {
            $path = base_path("routes/modules/{$module}.php");

            $this->assertFileExists(
                $path,
                "Módulo '{$module}' não tem arquivo de rotas: {$path}"
            );
        }
    }
}
