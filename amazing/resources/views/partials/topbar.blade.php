@php
  $scope = $currentScope ?? (request()->route('scope') ?? 'default');
  $portalLabel = $currentPortal['label'] ?? 'Amazing';
@endphp

<header class="app-topbar">
  <div class="app-topbar__slot">
    <a href="{{ route('hub.index', ['scope' => $scope]) }}" class="app-topbar__brand" title="Ir para o Hub">
      A
    </a>
  </div>

  <div class="app-topbar__searchWrap">
    <input
      class="app-topbar__search"
      type="search"
      placeholder="Pesquisar no sistema…"
    />
  </div>

  <div class="app-topbar__meta">
    <span class="hidden md:inline">{{ $portalLabel }}</span>
    <span class="app-topbar__pill">{{ $scope }}</span>
  </div>
</header>
