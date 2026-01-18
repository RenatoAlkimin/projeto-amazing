@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Usuários')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Usuários</h1>

    {{-- Criação de usuário fica pro Amazing (etapa futura) --}}
    <span class="text-sm text-gray-500">Criação de contas: apenas no Painel Amazing</span>
  </div>

  @if (session('success'))
    <div class="mb-4 p-3 rounded bg-green-50 text-green-700">
      {{ session('success') }}
    </div>
  @endif

  <div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-700">
        <tr>
          <th class="p-3 text-left">ID</th>
          <th class="p-3 text-left">Nome</th>
          <th class="p-3 text-left">Username</th>
          <th class="p-3 text-left">Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $user)
          <tr class="border-t">
            <td class="p-3">{{ $user->id }}</td>
            <td class="p-3">{{ $user->name }}</td>
            <td class="p-3 font-mono">{{ $user->username }}</td>
            <td class="p-3">
              <a class="underline"
                 href="{{ route('controladoria.users.access.edit', ['scope' => $scope, 'user' => $user->id]) }}">
                Gerenciar acessos
              </a>
            </td>
          </tr>
        @empty
          <tr class="border-t">
            <td colspan="4" class="p-6 text-center text-gray-500">
              Nenhum usuário encontrado.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
