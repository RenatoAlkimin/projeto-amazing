@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Financeiro')
@section('page_title', 'Financeiro')
@section('page_subtitle', 'Caixa (placeholder)')

@section('content')
  <div class="space-y-6">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div class="bg-white border rounded-xl p-5">
        <div class="text-xs uppercase tracking-wide text-gray-500">Saldo</div>
        <div class="text-2xl font-bold mt-2">R$ —</div>
      </div>

      <div class="bg-white border rounded-xl p-5">
        <div class="text-xs uppercase tracking-wide text-gray-500">A receber</div>
        <div class="text-2xl font-bold mt-2">R$ —</div>
      </div>

      <div class="bg-white border rounded-xl p-5">
        <div class="text-xs uppercase tracking-wide text-gray-500">A pagar</div>
        <div class="text-2xl font-bold mt-2">R$ —</div>
      </div>
    </section>

    <section class="bg-white border rounded-xl p-5">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Links rápidos</h2>
        <div class="text-xs text-gray-500">
          Rota: <code class="px-2 py-1 bg-gray-100 rounded">financeiro.index</code>
        </div>
      </div>

      <div class="mt-4 flex flex-wrap gap-2">
        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('hub.index', ['scope' => $scope]) }}">← Hub</a>

        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('comercial.index', ['scope' => $scope]) }}">Comercial</a>

        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('marketing.index', ['scope' => $scope]) }}">Marketing</a>

        <a class="px-3 py-2 border rounded-lg hover:bg-gray-50 transition"
           href="{{ route('rh.index', ['scope' => $scope]) }}">RH</a>
      </div>
    </section>
  </div>
@endsection
