<?php

namespace App\Support\Access;

use App\Models\Permission;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PermissionChecker
{
    public function __construct(
        private readonly StoreEntitlements $entitlements,
    ) {}

    public function can(User $user, string $scope, string $permissionKey): bool
    {
        $permissionKey = (string) $permissionKey;
        if ($permissionKey === '') return true;

        $permission = Permission::query()->where('key', $permissionKey)->first();
        if (! $permission) return false;

        // Se a loja não tem a action (ou módulo) habilitada, nem adianta RBAC
        if (! $this->entitlements->actionEnabled($scope, $permission)) {
            return false;
        }

        $store = Store::query()->where('scope_slug', $scope)->first();
        if (! $store) return false;

        // Override por usuário (deny > allow)
        $override = DB::table('store_user_permissions')
            ->where('store_id', $store->id)
            ->where('user_id', $user->id)
            ->where('permission_id', $permission->id)
            ->value('effect');

        if ($override === 'deny') return false;
        if ($override === 'allow') return true;

        // Role do usuário nessa loja (vem do pivot store_user.role_id)
        $roleId = $user->stores()
            ->where('stores.id', $store->id)
            ->value('store_user.role_id');

        if (! $roleId) return false;

        return DB::table('role_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permission->id)
            ->exists();
    }
}
