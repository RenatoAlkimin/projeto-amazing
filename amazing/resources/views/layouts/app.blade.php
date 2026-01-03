<body class="bg-slate-900 text-gray-900">
  <div class="min-h-screen flex flex-col">
    @include('partials.topbar')

    <div class="flex flex-1">
      @include('partials.sidebar')

      <main class="flex-1 bg-gray-50">
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
