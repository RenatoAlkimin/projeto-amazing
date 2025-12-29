<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $title ?? 'Amazing' }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">
  <div class="flex min-h-screen">
    <aside class="w-64 bg-white border-r">
      <div class="p-4 font-semibold">Amazing • Vaapty</div>

      <nav class="px-2 space-y-1">
        <a class="block rounded px-3 py-2 hover:bg-gray-100 {{ request()->routeIs('hub.*') ? 'bg-gray-100' : '' }}"
           href="{{ route('hub.home') }}">Hub</a>

        <a class="block rounded px-3 py-2 hover:bg-gray-100 {{ request()->routeIs('comercial.*') ? 'bg-gray-100' : '' }}"
           href="{{ route('comercial.home') }}">Comercial</a>

        <a class="block rounded px-3 py-2 hover:bg-gray-100 {{ request()->routeIs('financeiro.*') ? 'bg-gray-100' : '' }}"
           href="{{ route('financeiro.home') }}">Financeiro</a>

        <a class="block rounded px-3 py-2 hover:bg-gray-100 {{ request()->routeIs('central.*') ? 'bg-gray-100' : '' }}"
           href="{{ route('central.home') }}">Central</a>
      </nav>
    </aside>

    <main class="flex-1">
      <header class="h-14 bg-white border-b flex items-center px-6">
        <div class="font-medium">{{ $header ?? 'Hub' }}</div>
      </header>

      <div class="p-6">
        @yield('content')
      </div>
    </main>
  </div>
</body>
</html>
