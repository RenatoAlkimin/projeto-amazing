@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Hub')
@section('page_title', 'Hub')
@section('page_subtitle', 'Visão geral do sistema')

@section('content')
  <div class="space-y-6">
    {{-- Page Header (reutilizável) --}}
    @include('partials.page-header', ['showScope' => true])

    {{-- Atalhos --}}
    <section class="card p-6">
      <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
          <h2 class="text-lg card-title">Atalhos</h2>
          <p class="mt-1 text-sm card-subtitle">
            Navegação rápida entre módulos.
          </p>
        </div>

        <div class="shrink-0 text-xs card-subtitle">
          <span class="mr-2">Rota:</span>
          <code class="code-pill">hub.index</code>
        </div>
      </div>

      <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <a href="{{ route('comercial.index', ['scope' => $scope]) }}"
           class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">
                Comercial
              </div>
              <div class="mt-1 text-sm card-subtitle">
                Vendas, funil, oportunidades
              </div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>

        <a href="{{ route('financeiro.index', ['scope' => $scope]) }}"
           class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">
                Financeiro
              </div>
              <div class="mt-1 text-sm card-subtitle">
                Caixa, contas, conciliação
              </div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>

        <a href="{{ route('marketing.index', ['scope' => $scope]) }}"
           class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">
                Marketing
              </div>
              <div class="mt-1 text-sm card-subtitle">
                Campanhas, leads, canais
              </div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>

        <a href="{{ route('rh.index', ['scope' => $scope]) }}"
           class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">
                RH
              </div>
              <div class="mt-1 text-sm card-subtitle">
                Pessoas, escala, performance
              </div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>
      </div>
    </section>

    {{-- Status --}}
    <section class="card p-6">
      <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
          <h2 class="text-lg card-title">Status do protótipo</h2>
          <p class="mt-1 text-sm card-subtitle">
            Notas rápidas do que já está pronto e como está organizado.
          </p>
        </div>
      </div>

      <ul class="mt-4 space-y-2 text-sm text-gray-700 list-disc list-inside">
        <li>
          Sidebar vem de <code class="code-pill">config/modules.php</code> + <code class="code-pill">config/portals.php</code>
        </li>
        <li>
          Rotas escopadas em <code class="code-pill">/s/{scope}</code>
        </li>
        <li>
          Enforcement por middleware <code class="code-pill">module_enabled:&lt;módulo&gt;</code>
        </li>
      </ul>
    </section>
  </div>
@endsection
