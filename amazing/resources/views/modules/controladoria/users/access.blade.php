@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Acessos do usuário')

@section('content')
  <div class="mb-4">
    <a class="underline text-sm text-gray-600"
       href="{{ route('controladoria.users.index', ['scope' => $scope]) }}">
      ← Voltar
    </a>
  </div>

  <div class="space-y-4">
    <div class="card p-6">
      <h1 class="text-2xl font-semibold">Acessos</h1>
      <p class="text-sm text-gray-600 mt-1">
        Usuário: <span class="font-medium">{{ $user->name }}</span>
        (<span class="font-mono">{{ $user->username }}</span>)
      </p>

      @if (session('success'))
        <div class="mt-4 p-3 rounded bg-green-50 text-green-700">
          {{ session('success') }}
        </div>
      @endif
    </div>

    <form method="POST"
          action="{{ route('controladoria.users.access.update', ['scope' => $scope, 'user' => $user->id]) }}"
          class="card p-6 space-y-4">
      @csrf

      <div class="overflow-hidden rounded border">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="p-3 text-left">Acesso</th>
              <th class="p-3 text-left">Loja</th>
              <th class="p-3 text-left">Scope</th>
              <th class="p-3 text-left">Role</th>
              <th class="p-3 text-left">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($stores as $store)
              @php
                $m = $memberships->get($store->id);
                $checked = (bool) $m;
                $currentRoleId = $m?->pivot?->role_id;
                $currentStatus = $m?->pivot?->status ?? 'inactive';
              @endphp

              <tr class="border-t">
                <td class="p-3">
                  <input type="checkbox"
                         name="stores[]"
                         value="{{ $store->id }}"
                         @checked($checked)
                  />
                </td>

                <td class="p-3">{{ $store->name }}</td>
                <td class="p-3 font-mono">{{ $store->scope_slug }}</td>

                <td class="p-3">
                  <select name="role_id[{{ $store->id }}]" class="border rounded px-2 py-1 w-full">
                    <option value="">(sem role)</option>
                    @foreach ($roles as $role)
                      <option value="{{ $role->id }}" @selected((int)$currentRoleId === (int)$role->id)>
                        {{ $role->name }} ({{ $role->key }})
                      </option>
                    @endforeach
                  </select>
                </td>

                <td class="p-3">
                  <span class="text-xs px-2 py-1 rounded {{ $currentStatus === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $currentStatus }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="flex items-center justify-end gap-2">
        <button class="px-4 py-2 rounded bg-black text-white" type="submit">
          Salvar acessos
        </button>
      </div>
    </form>
  </div>
@endsection
