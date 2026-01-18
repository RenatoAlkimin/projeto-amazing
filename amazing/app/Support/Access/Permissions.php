<?php

namespace App\Support\Access;

use App\Models\Permission;
use App\Models\Store;
use App\Models\User;

class Permissions
{
    private ?array $knownKeys = null;
    private array $rolePermCache = [];

    public function isKnown(string $key): bool
    {
        $key = trim($key);
        if ($key === '') return false;

        if ($this->knownKeys === null) {
            $this->knownKeys = Permission::query()->pluck('key')->all();
        }

        return in_array($key, $this->knownKeys, true);
    }

    public function userHas(User $user, ?string $permissionKey, ?string $scopeSlug): bool
    {
        $permissionKey = (string) $permissionKey;
        if ($permissionKey === '') return true;

        // Superadmin interno pode tudo (mesmo no portal vaapty)
        if (method_exists($user, 'isSuperadmin') && $user->isSuperadmin()) {
            return true;
        }

        if (!$this->isKnown($permissionKey)) {
            return false;
        }

        $scopeSlug = (string) $scopeSlug;
        if ($scopeSlug === '') return false;

        $storeId = Store::query()->where('scope_slug', $scopeSlug)->value('id');
        if (!$storeId) return false;

        $roleId = $user->stores()
            ->where('stores.id', $storeId)
            ->wherePivot('status', 'active')
            ->value('store_user.role_id');

        if (!$roleId) return false;

        $perms = $this->rolePermissions((int) $roleId);

        return in_array($permissionKey, $perms, true);
    }

    private function rolePermissions(int $roleId): array
    {
        if (isset($this->rolePermCache[$roleId])) {
            return $this->rolePermCache[$roleId];
        }

        $keys = \App\Models\Role::query()
            ->whereKey($roleId)
            ->with('permissions:key')
            ->first()
            ?->permissions
            ?->pluck('key')
            ?->all() ?? [];

        return $this->rolePermCache[$roleId] = $keys;
    }
}
