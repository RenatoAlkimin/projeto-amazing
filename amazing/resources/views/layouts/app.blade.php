<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Amazing')</title>

  {{-- Vite / Tailwind (não roda no ambiente de testes pra não exigir manifest) --}}
  @unless(app()->environment('testing'))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endunless
</head>

<body class="app-shell text-gray-900">
  <div class="app-frame">
    {{-- Topbar --}}
    @include('partials.topbar')

    <div class="app-body">
      {{-- Sidebar --}}
      @include('partials.sidebar')

      {{-- Conteúdo (surface com curva) --}}
      <main class="app-surface">
        <div class="p-6">
          @yield('content')
        </div>
      </main>
    </div>
  </div>
</body>
</html>
