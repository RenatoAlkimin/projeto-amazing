@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Financeiro')
@section('page_title', 'Financeiro')
@section('page_subtitle', 'Caixa (placeholder)')

@section('content')
  <div class="space-y-6">
    @include('partials.page-header')

    {{-- KPIs --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div class="card p-5">
        <div class="text-xs uppercase tracking-wide text-[hsl(var(--muted))]">Saldo</div>
        <div class="text-2xl font-bold mt-2">R$ —</div>
      </div>

      <div class="card p-5">
        <div class="text-xs uppercase tracking-wide text-[hsl(var(--muted))]">A receber</div>
        <div class="text-2xl font-bold mt-2">R$ —</div>
      </div>

      <div class="card p-5">
        <div class="text-xs uppercase tracking-wide text-[hsl(var(--muted))]">A pagar</div>
        <div class="text-2xl font-bold mt-2">R$ —</div>
      </div>
    </section>

    {{-- Links rápidos --}}
    <section class="card p-6">
      <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
          <h2 class="text-lg card-title">Links rápidos</h2>
          <p class="mt-1 text-sm card-subtitle">Navegação entre módulos</p>
        </div>

        <div class="shrink-0 text-xs card-subtitle">
          <span class="mr-2">Rota:</span>
          <code class="code-pill">financeiro.index</code>
        </div>
      </div>

      <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <a href="{{ route('hub.index', ['scope' => $scope]) }}" class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">Hub</div>
              <div class="mt-1 text-sm card-subtitle">Visão geral e atalhos</div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>

        <a href="{{ route('comercial.index', ['scope' => $scope]) }}" class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">Comercial</div>
              <div class="mt-1 text-sm card-subtitle">Vendas e oportunidades</div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>

        <a href="{{ route('marketing.index', ['scope' => $scope]) }}" class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">Marketing</div>
              <div class="mt-1 text-sm card-subtitle">Campanhas e leads</div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>

        <a href="{{ route('rh.index', ['scope' => $scope]) }}" class="tile group block p-4">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold tracking-tight transition group-hover:text-[hsl(var(--primary))]">RH</div>
              <div class="mt-1 text-sm card-subtitle">Pessoas e performance</div>
            </div>
            <span class="tile-arrow" aria-hidden="true">↗</span>
          </div>
        </a>
      </div>
    </section>
  </div>
@endsection
