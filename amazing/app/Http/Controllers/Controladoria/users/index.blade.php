@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Usuários')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Usuários</h1>
  </div>

  <div class="rounded border bg-white overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">ID</th>
          <th class="text-left p-3">Nome</th>
          <th class="text-left p-3">Username</th>
          <th class="text-left p-3"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          <tr class="border-t">
            <td class="p-3">{{ $u->id }}</td>
            <td class="p-3">{{ $u->name }}</td>
            <td class="p-3 font-mono">{{ $u->username }}</td>
            <td class="p-3 text-right">
              <a class="px-3 py-1 rounded border"
                 href="{{ route('controladoria.users.access.edit', ['scope' => $scope, 'user' => $u->id]) }}">
                Acessos
              </a>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="p-6 text-center text-gray-500">Nenhum usuário.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
