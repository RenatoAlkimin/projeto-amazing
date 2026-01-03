<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Amazing')</title>

  {{-- Vite / Tailwind --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-900 text-gray-900">
  <div class="min-h-screen flex flex-col">
    {{-- Topbar (conectada no sidebar) --}}
    @include('partials.topbar')

    <div class="flex flex-1">
      {{-- Sidebar (rail) --}}
      @include('partials.sidebar')

      {{-- Conteúdo --}}
      <main class="flex-1 bg-gray-50">
        {{-- Header interno do conteúdo (abaixo do topbar) --}}
        <header class="sticky top-14 z-10 bg-white border-b">
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
