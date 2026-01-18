@extends('layouts.guest')

@section('title', 'Entrar')

@section('content')
  <h1 class="text-2xl font-semibold mb-6">Entrar</h1>

  @if ($errors->any())
    <div class="mb-4 p-3 rounded bg-red-50 text-red-800 text-sm">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="/login" class="space-y-4">
    @csrf

    <div>
      <label class="block text-sm font-medium mb-1">Usuário</label>
      <input name="username" value="{{ old('username') }}" required autofocus
             class="w-full rounded-lg border p-2" />
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Senha</label>
      <input type="password" name="password" required class="w-full rounded-lg border p-2" />
    </div>

    <label class="flex items-center gap-2 text-sm">
      <input type="checkbox" name="remember" class="rounded" />
      Lembrar
    </label>

    <button type="submit" class="w-full rounded-lg bg-black text-white py-2">
      Entrar
    </button>
  </form>
@endsection
