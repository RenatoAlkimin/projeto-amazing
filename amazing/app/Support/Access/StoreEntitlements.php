<?php

namespace App\Support\Access;

use App\Models\Permission;
use App\Models\Store;
use App\Models\StoreEntitlement;
use App\Models\StoreModule;

class StoreEntitlements
{
    public function moduleEnabled(string $scope, string $moduleKey): bool
    {
        $store = Store::query()->where('scope_slug', $scope)->first();
        if (! $store) return false;

        $row = StoreModule::query()
            ->where('store_id', $store->id)
            ->where('module_key', $moduleKey)
            ->first();

        // Sem contrato no DB ainda? (fase de transição) -> fallback pro config tenants
        if (! $row) {
            return in_array($moduleKey, (new TenantModules())->allowedModules($scope), true);
        }

        return $row->status === 'enabled';
    }

    public function actionEnabled(string $scope, Permission $permission): bool
    {
        // Se não estiver atrelada a módulo, consideramos "global" (não recomendo, mas não quebra)
        $moduleKey = (string) ($permission->module_key ?? '');
        if ($moduleKey !== '' && ! $this->moduleEnabled($scope, $moduleKey)) {
            return false;
        }

        // Ação "geral" do módulo: não precisa entitlement explícito
        if (! $permission->is_addon) {
            return true;
        }

        $store = Store::query()->where('scope_slug', $scope)->first();
        if (! $store) return false;

        $row = StoreEntitlement::query()
            ->where('store_id', $store->id)
            ->where('permission_id', $permission->id)
            ->first();

        // Extra: só libera se estiver explicitamente enabled
        return $row?->status === 'enabled';
    }
}
