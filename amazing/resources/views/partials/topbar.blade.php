@php
  $scope = $currentScope ?? (request()->route('scope') ?? 'default');

  $portalLabel = $currentPortalLabel
    ?? ($currentPortal['label'] ?? 'Amazing');

  // Evita erro no php artisan test quando não existe public/build/manifest.json
  try {
      $logoUrl = Vite::asset('resources/images/icon-vaapty.png');
  } catch (\Throwable $e) {
      // fallback (coloque o arquivo em: public/images/icon-vaapty.png)
      $logoUrl = asset('images/icon-vaapty.png');
  }
@endphp

<header class="app-topbar">
  {{-- Slot fixo alinhado com a sidebar --}}
  <div class="app-topbar__slot">
    <a href="{{ route('hub.index', ['scope' => $scope]) }}" class="app-topbar__brand" title="Ir para o Hub">
      <img
        src="{{ $logoUrl }}"
        alt="Vaapty"
        class="app-topbar__logo"
      />
    </a>
  </div>

  <div class="app-topbar__searchWrap">
    <div class="app-topbar__searchField">
      <svg class="app-topbar__searchIcon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" />
      </svg>

      <input
        class="app-topbar__search"
        type="search"
        placeholder="Pesquisar no sistema…"
      />

      <kbd class="app-topbar__kbd">Ctrl K</kbd>
    </div>

    <button type="button" class="app-topbar__plus" aria-label="Criar">
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" class="w-4 h-4" style="color: rgba(255,255,255,.9)">
        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
      </svg>
    </button>
  </div>

  <div class="app-topbar__meta">
    <span class="hidden md:inline">{{ $portalLabel }}</span>
    <span class="app-topbar__pill">{{ $scope }}</span>
  </div>
</header>
