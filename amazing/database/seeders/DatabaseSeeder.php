<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * 1) Stores base (pra não quebrar /s/default e testes)
         */
        $defaultStore = Store::query()->firstOrCreate(
            ['scope_slug' => 'default'],
            ['name' => 'Default', 'status' => 'active']
        );

        $loja002 = Store::query()->firstOrCreate(
            ['scope_slug' => 'loja_002'],
            ['name' => 'Loja 002', 'status' => 'active']
        );

        /**
         * 2) Roles (internal + store)
         */
        $roles = [
            // Internal (Amazing)
            ['key' => 'superadmin', 'name' => 'Superadmin', 'scope_type' => 'internal', 'level' => 999],

            // Store-scoped (Vaapty)
            ['key' => 'adm', 'name' => 'Admin', 'scope_type' => 'store', 'level' => 100],
            ['key' => 'franchising', 'name' => 'Franchising', 'scope_type' => 'store', 'level' => 95],
            ['key' => 'franqueado', 'name' => 'Franqueado', 'scope_type' => 'store', 'level' => 90],

            ['key' => 'diretor_financeiro', 'name' => 'Diretor Financeiro', 'scope_type' => 'store', 'level' => 80],
            ['key' => 'diretor_comercial_franqueado', 'name' => 'Diretor Comercial (Franqueado)', 'scope_type' => 'store', 'level' => 80],

            ['key' => 'regional', 'name' => 'Regional', 'scope_type' => 'store', 'level' => 70],
            ['key' => 'gerente', 'name' => 'Gerente', 'scope_type' => 'store', 'level' => 60],

            ['key' => 'marketing', 'name' => 'Marketing', 'scope_type' => 'store', 'level' => 30],
            ['key' => 'rh', 'name' => 'RH', 'scope_type' => 'store', 'level' => 30],

            ['key' => 'negociador', 'name' => 'Negociador', 'scope_type' => 'store', 'level' => 20],
            ['key' => 'sdr', 'name' => 'SDR', 'scope_type' => 'store', 'level' => 10],
        ];

        foreach ($roles as $r) {
            Role::query()->firstOrCreate(
                ['key' => $r['key']],
                ['name' => $r['name'], 'scope_type' => $r['scope_type'], 'level' => $r['level']]
            );
        }

        $superadminRole = Role::query()->where('key', 'superadmin')->first();
        $admRole = Role::query()->where('key', 'adm')->first();

        /**
         * 3) Permissions (catálogo inicial)
         * Dica: mantenha keys estáveis — isso vira contrato entre UI/rotas e RBAC.
         */
        $permissions = [
            ['key' => 'diagnostics.view', 'description' => 'Ver Diagnostics (internal)'],
            ['key' => 'controladoria.manage', 'description' => 'Gerenciar controladoria (internal)'],

            ['key' => 'hub.view', 'description' => 'Acessar Hub'],
            ['key' => 'comercial.view', 'description' => 'Ver Comercial'],
            ['key' => 'financeiro.view', 'description' => 'Ver Financeiro'],
            ['key' => 'marketing.view', 'description' => 'Ver Marketing'],
            ['key' => 'rh.view', 'description' => 'Ver RH'],

            // base para evoluir depois
            ['key' => 'stores.manage', 'description' => 'Gerenciar lojas'],
            ['key' => 'users.manage', 'description' => 'Gerenciar usuários'],
            ['key' => 'modules.manage', 'description' => 'Gerenciar módulos/contratos'],
        ];

        foreach ($permissions as $p) {
            Permission::query()->firstOrCreate(
                ['key' => $p['key']],
                ['description' => $p['description']]
            );
        }

        /**
         * 4) role_permissions (matriz inicial)
         */
        $rolePermMatrix = [
            // internal
            'superadmin' => [
                'diagnostics.view',
                'controladoria.manage',
                'hub.view',
                'comercial.view',
                'financeiro.view',
                'marketing.view',
                'rh.view',
                'stores.manage',
                'users.manage',
                'modules.manage',
            ],

            // store roles
            'adm' => [
                'hub.view',
                'comercial.view',
                'financeiro.view',
                'marketing.view',
                'rh.view',
                'stores.manage',
                'users.manage',
                'modules.manage',
            ],

            'franchising' => [
                'hub.view',
                'comercial.view',
                'financeiro.view',
                'marketing.view',
                'rh.view',
            ],

            'franqueado' => [
                'hub.view',
                'comercial.view',
                'financeiro.view',
            ],

            'diretor_financeiro' => [
                'hub.view',
                'financeiro.view',
            ],

            'diretor_comercial_franqueado' => [
                'hub.view',
                'comercial.view',
                'marketing.view',
            ],

            'regional' => [
                'hub.view',
                'comercial.view',
                'financeiro.view',
                'marketing.view',
                'rh.view',
            ],

            'gerente' => [
                'hub.view',
                'comercial.view',
                'financeiro.view',
                'marketing.view',
                'rh.view',
            ],

            'marketing' => [
                'hub.view',
                'marketing.view',
            ],

            'rh' => [
                'hub.view',
                'rh.view',
            ],

            'negociador' => [
                'hub.view',
                'comercial.view',
            ],

            'sdr' => [
                'hub.view',
                'comercial.view',
            ],
        ];

        // Pre-carga de permissões por key
        $permByKey = Permission::query()->pluck('id', 'key')->all();

        foreach ($rolePermMatrix as $roleKey => $permKeys) {
            $role = Role::query()->where('key', $roleKey)->first();
            if (!$role) {
                continue;
            }

            $permIds = [];
            foreach ($permKeys as $k) {
                if (isset($permByKey[$k])) {
                    $permIds[] = (int) $permByKey[$k];
                }
            }

            // sync sem apagar outros (mas aqui normalmente é fresh)
            $role->permissions()->syncWithoutDetaching($permIds);
        }

        /**
         * 5) Usuário base (Superadmin do painel Amazing)
         * - internal_role_id precisa estar setado, senão ensure_portal_access dá 403
         * - password precisa estar hash
         */
        $adminUsername = env('AMAZING_SEED_SUPERADMIN_USERNAME', 'admin');
        $adminPassword = env('AMAZING_SEED_SUPERADMIN_PASSWORD', 'admin');

        $admin = User::query()->firstOrCreate(
            ['username' => $adminUsername],
            [
                'name' => 'Superadmin',
                'password' => Hash::make($adminPassword),
            ]
        );

        // Garante internal_role_id
        if ($superadminRole && (int) ($admin->internal_role_id ?? 0) !== (int) $superadminRole->id) {
            $admin->internal_role_id = $superadminRole->id;
            $admin->save();
        }

        /**
         * 6) Membership padrão do admin para conseguir acessar VAAPTY no dev
         */
        if ($admRole) {
            $admin->stores()->syncWithoutDetaching([
                $defaultStore->id => ['role_id' => $admRole->id, 'status' => 'active'],
                $loja002->id => ['role_id' => $admRole->id, 'status' => 'active'],
            ]);
        }
    }
}
