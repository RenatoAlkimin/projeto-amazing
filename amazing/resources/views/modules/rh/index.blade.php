@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'RH')
@section('page_title', 'RH')
@section('page_subtitle', 'Pessoas (placeholder)')

@section('content')
  <div class="space-y-6">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div class="bg-white border rounded-xl p-5">
        <div class="text-xs uppercase tracking-wide text-gray-500">Colaboradores</div>
        <div class="text-2xl font-bold mt-2">—</div>
      </div>

      <div class="bg-white border rounded-xl p-5">
        <div class="text-xs uppercase tracking-wide text-gray-500">Turnover</div>
        <div class="text-2xl font-bold mt-2">—</div>
      </div>

      <div class="bg-white border rounded-xl p-5">
        <div class="text-xs uppercase tracking-wide text-gray-500">Aberturas</div>
        <div class="text-2xl font-bold mt-2">—</div>
      </div>
    </section>

    <section class="bg-white border rounded-xl p-5">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Links rápidos</h2>
        <div class="text-xs text-gray-500">
          Rota: <code class="px-2 py-1 bg-gray-100 rounded">rh.index</code>
        </div>
      </div>

      <div class="mt-4 flex flex-wrap gap-2">
        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('hub.index', ['scope' => $scope]) }}">← Hub</a>

        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('comercial.index', ['scope' => $scope]) }}">Comercial</a>

        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('financeiro.index', ['scope' => $scope]) }}">Financeiro</a>

        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('marketing.index', ['scope' => $scope]) }}">Marketing</a>
      </div>
    </section>
  </div>
@endsection
