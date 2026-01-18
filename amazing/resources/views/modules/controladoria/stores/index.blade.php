@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Lojas')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Lojas</h1>

    <a class="px-4 py-2 rounded bg-black text-white"
       href="{{ route('controladoria.stores.create', ['scope' => $scope]) }}">
      Nova loja
    </a>
  </div>

  @if (session('success'))
    <div class="mb-4 p-3 rounded bg-green-50 text-green-800 text-sm">
      {{ session('success') }}
    </div>
  @endif

  <div class="rounded border bg-white overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Scope</th>
          <th class="text-left p-3">Nome</th>
          <th class="text-left p-3">Status</th>
          <th class="text-left p-3">Criada em</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($stores as $store)
          <tr class="border-t">
            <td class="p-3 font-mono">{{ $store->scope_slug }}</td>
            <td class="p-3">{{ $store->name }}</td>
            <td class="p-3">{{ $store->status }}</td>
            <td class="p-3">{{ $store->created_at?->format('Y-m-d H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="p-6 text-center text-gray-500">Nenhuma loja cadastrada.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
