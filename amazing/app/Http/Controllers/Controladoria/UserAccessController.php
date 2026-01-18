<?php

namespace App\Http\Controllers\Controladoria;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function index(string $scope)
    {
        $users = User::query()->orderBy('id', 'desc')->get();

        return view('modules.controladoria.users.index', compact('users'));
    }

    public function edit(string $scope, User $user)
    {
        $stores = Store::query()->orderBy('scope_slug')->get();
        $roles = Role::query()
            ->where('scope_type', 'store')
            ->orderBy('level', 'desc')
            ->get();

        $memberships = $user->stores()
            ->withPivot(['role_id', 'status'])
            ->get()
            ->keyBy('id');

        return view('modules.controladoria.users.access', compact('user', 'stores', 'roles', 'memberships'));
    }

    public function update(string $scope, Request $request, User $user)
    {
        $selectedStoreIds = array_map('intval', (array) $request->input('stores', []));
        $roleMap = (array) $request->input('role_id', []);

        $sync = [];

        foreach ($selectedStoreIds as $storeId) {
            $roleId = isset($roleMap[$storeId]) ? (int) $roleMap[$storeId] : null;

            $sync[$storeId] = [
                'role_id' => $roleId ?: null,
                'status' => 'active',
            ];
        }

        $user->stores()->sync($sync);

        return redirect()
            ->route('controladoria.users.access.edit', ['scope' => $scope, 'user' => $user->id])
            ->with('success', 'Acessos atualizados.');
    }
}
