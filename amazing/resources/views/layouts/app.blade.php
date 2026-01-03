<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Amazing')</title>

  {{-- Vite / Tailwind --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        {{-- Header interno do conteúdo --}}
        <header class="app-contentHeader">
          <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="text-lg font-semibold">
                @yield('page_title', 'Painel')
              </div>

              @hasSection('page_subtitle')
                <div class="text-sm text-gray-500">@yield('page_subtitle')</div>
              @endif
            </div>

            <div class="text-xs text-gray-500">
              Scope: <span class="font-mono">{{ request()->route('scope') ?? 'default' }}</span>
            </div>
          </div>
        </header>

        <div class="p-6">
          @yield('content')
        </div>
      </main>
    </div>
  </div>
</body>
</html>
