@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Hub')
@section('page_title', 'Hub')
@section('page_subtitle', 'Visão geral do sistema')

@section('content')
  <div class="space-y-6">
    <section class="bg-white border rounded-xl p-5">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h2 class="text-lg font-semibold">Atalhos</h2>
          <p class="text-sm text-gray-600 mt-1">
            Navegação rápida entre módulos (scope: <span class="font-mono">{{ $scope }}</span>).
          </p>
        </div>
        <div class="text-xs text-gray-500">
          Rota: <code class="px-2 py-1 bg-gray-100 rounded">hub.index</code>
        </div>
      </div>

      <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <a href="{{ route('comercial.index', ['scope' => $scope]) }}"
           class="block p-4 border rounded-xl hover:bg-gray-50 transition">
          <div class="font-semibold">Comercial</div>
          <div class="text-sm text-gray-600 mt-1">Vendas, funil, oportunidades</div>
        </a>

        <a href="{{ route('financeiro.index', ['scope' => $scope]) }}"
           class="block p-4 border rounded-xl hover:bg-gray-50 transition">
          <div class="font-semibold">Financeiro</div>
          <div class="text-sm text-gray-600 mt-1">Caixa, contas, conciliação</div>
        </a>

        <a href="{{ route('marketing.index', ['scope' => $scope]) }}"
           class="block p-4 border rounded-xl hover:bg-gray-50 transition">
          <div class="font-semibold">Marketing</div>
          <div class="text-sm text-gray-600 mt-1">Campanhas, leads, canais</div>
        </a>

        <a href="{{ route('rh.index', ['scope' => $scope]) }}"
           class="block p-4 border rounded-xl hover:bg-gray-50 transition">
          <div class="font-semibold">RH</div>
          <div class="text-sm text-gray-600 mt-1">Pessoas, escala, performance</div>
        </a>
      </div>
    </section>

    <section class="bg-white border rounded-xl p-5">
      <h2 class="text-lg font-semibold">Status do protótipo</h2>
      <ul class="mt-3 space-y-2 text-sm text-gray-700 list-disc list-inside">
        <li>Sidebar vem de <code class="px-1 bg-gray-100 rounded">config/modules.php</code> + <code class="px-1 bg-gray-100 rounded">config/portals.php</code></li>
        <li>Rotas escopadas em <code class="px-1 bg-gray-100 rounded">/s/{scope}</code></li>
        <li>Enforcement por middleware <code class="px-1 bg-gray-100 rounded">module_enabled:&lt;módulo&gt;</code></li>
      </ul>
    </section>
  </div>
@endsection
