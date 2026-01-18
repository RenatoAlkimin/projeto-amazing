@extends('layouts.app')

@php($scope = request()->route('scope') ?? 'default')

@section('title', 'Nova loja')
@section('content')
  <div class="max-w-xl space-y-4">
    <h1 class="text-2xl font-semibold">Nova loja</h1>

    @if ($errors->any())
      <div class="p-3 rounded bg-red-50 text-red-800 text-sm">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('controladoria.stores.store', ['scope' => $scope]) }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Scope (slug)</label>
        <input name="scope_slug" value="{{ old('scope_slug') }}" required
               placeholder="ex: loja-001"
               class="w-full rounded border p-2" />
        <p class="text-xs text-gray-500 mt-1">Aparece na URL: /s/{scope}</p>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Nome</label>
        <input name="name" value="{{ old('name') }}" required
               placeholder="ex: Vaapty Sorocaba"
               class="w-full rounded border p-2" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <select name="status" class="w-full rounded border p-2">
          <option value="active" @selected(old('status', 'active') === 'active')>active</option>
          <option value="inactive" @selected(old('status') === 'inactive')>inactive</option>
        </select>
      </div>

      <div class="flex gap-2">
        <button class="px-4 py-2 rounded bg-black text-white" type="submit">Criar</button>
        <a class="px-4 py-2 rounded border"
           href="{{ route('controladoria.stores.index', ['scope' => $scope]) }}">
          Cancelar
        </a>
      </div>
    </form>
  </div>
@endsection
