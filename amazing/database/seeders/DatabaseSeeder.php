<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário base (superadmin do painel Amazing)
        $admin = User::factory()->create([
            'name' => 'Superadmin',
            'username' => env('AMAZING_SEED_SUPERADMIN_USERNAME', 'admin'),
            'password' => env('AMAZING_SEED_SUPERADMIN_PASSWORD', 'admin'),
        ]);

        // Stores base (pra não quebrar /s/default e testes)
        $defaultStore = Store::query()->firstOrCreate(
            ['scope_slug' => 'default'],
            ['name' => 'Default', 'status' => 'active']
        );

        $loja002 = Store::query()->firstOrCreate(
            ['scope_slug' => 'loja_002'],
            ['name' => 'Loja 002', 'status' => 'active']
        );

        // Roles (Etapa 4) — catálogo mínimo inicial
        $roles = [
            ['key' => 'superadmin', 'name' => 'Superadmin', 'scope_type' => 'internal', 'level' => 999],

            ['key' => 'adm', 'name' => 'Admin', 'scope_type' => 'store', 'level' => 100],
            ['key' => 'sdr', 'name' => 'SDR', 'scope_type' => 'store', 'level' => 10],
            ['key' => 'negociador', 'name' => 'Negociador', 'scope_type' => 'store', 'level' => 20],
            ['key' => 'rh', 'name' => 'RH', 'scope_type' => 'store', 'level' => 30],
            ['key' => 'marketing', 'name' => 'Marketing', 'scope_type' => 'store', 'level' => 30],
            ['key' => 'gerente', 'name' => 'Gerente', 'scope_type' => 'store', 'level' => 60],
            ['key' => 'regional', 'name' => 'Regional', 'scope_type' => 'store', 'level' => 70],
            ['key' => 'diretor_financeiro', 'name' => 'Diretor Financeiro', 'scope_type' => 'store', 'level' => 80],
            ['key' => 'diretor_comercial_franqueado', 'name' => 'Diretor Comercial (Franqueado)', 'scope_type' => 'store', 'level' => 80],
            ['key' => 'franchising', 'name' => 'Franchising', 'scope_type' => 'store', 'level' => 90],
        ];

        foreach ($roles as $r) {
            Role::query()->firstOrCreate(
                ['key' => $r['key']],
                ['name' => $r['name'], 'scope_type' => $r['scope_type'], 'level' => $r['level']]
            );
        }

        // Membership padrão do admin para conseguir acessar VAAPTY no dev
        $admRole = Role::query()->where('key', 'adm')->first();

        if ($admRole) {
            $admin->stores()->syncWithoutDetaching([
                $defaultStore->id => ['role_id' => $admRole->id, 'status' => 'active'],
                $loja002->id => ['role_id' => $admRole->id, 'status' => 'active'],
            ]);
        }
    }
}
