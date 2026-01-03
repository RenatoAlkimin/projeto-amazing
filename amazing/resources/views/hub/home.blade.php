@extends('layouts.app', ['title' => 'Amazing • Hub', 'header' => 'Hub'])

@section('content')
  @php
    $scope = $currentScope ?? 'default';
  @endphp
<h1 class="text-2xl font-semibold">Hub Amazing</h1>
  <p class="text-gray-600 mt-1">Pré-projeto (UI only).</p>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <a class="block bg-white border rounded p-4 hover:shadow" href="{{ route('comercial.index', ['scope' => $scope]) }}">
      <div class="font-medium">Comercial</div>
      <div class="text-sm text-gray-600">Pipeline, leads, propostas (placeholder)</div>
    </a>

    <a class="block bg-white border rounded p-4 hover:shadow" href="{{ route('financeiro.index', ['scope' => $scope]) }}">
      <div class="font-medium">Financeiro</div>
      <div class="text-sm text-gray-600">Recebíveis, extratos (placeholder)</div>
    </a>

    <a class="block bg-white border rounded p-4 hover:shadow" href="{{ route('central.index', ['scope' => $scope]) }}">
      <div class="font-medium">Central</div>
      <div class="text-sm text-gray-600">Franchising e hierarquia (placeholder)</div>
    </a>
  </div>
@endsection
