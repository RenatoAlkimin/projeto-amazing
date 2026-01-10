<?php

namespace App\Support\Access;

class TenantModules
{
    /**
     * @return string[]
     */
    public function allowedModules(string $scope): array
    {
        $catalog = array_keys((array) config('modules', []));

        $tenantModules = (array) config("tenants.{$scope}.modules", []);

        // fallback: default (bem importante pra não travar tudo quando scope não estiver mapeado)
        if ($tenantModules === []) {
            $defaultScope = (string) config('amazing.default_scope', 'default');
            $tenantModules = (array) config("tenants.{$defaultScope}.modules", []);
        }

        if (in_array('*', $tenantModules, true)) {
            return $catalog;
        }

        // filtra só o que existe no catálogo (evita lixo/typo)
        $allowed = [];
        foreach ($tenantModules as $m) {
            $m = (string) $m;
            if ($m !== '' && in_array($m, $catalog, true)) {
                $allowed[] = $m;
            }
        }

        return array_values(array_unique($allowed));
    }

    public function allows(string $scope, string $module): bool
    {
        return in_array($module, $this->allowedModules($scope), true);
    }
}
