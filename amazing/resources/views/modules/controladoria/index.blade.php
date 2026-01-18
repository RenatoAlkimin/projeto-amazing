@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Controladoria')
@section('content')
  <div class="space-y-4">
    <h1 class="text-2xl font-semibold">Controladoria</h1>

    <div class="flex gap-2 flex-wrap">
      @if (\Illuminate\Support\Facades\Route::has('controladoria.stores.index'))
        <a class="inline-block px-4 py-2 rounded bg-black text-white"
           href="{{ route('controladoria.stores.index', ['scope' => $scope]) }}">
          Gerenciar lojas
        </a>
      @endif

      @if (\Illuminate\Support\Facades\Route::has('controladoria.users.index'))
        <a class="inline-block px-4 py-2 rounded bg-black text-white"
           href="{{ route('controladoria.users.index', ['scope' => $scope]) }}">
          Gerenciar usuários
        </a>
      @endif
    </div>
  </div>
@endsection
