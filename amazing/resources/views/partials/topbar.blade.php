@php
  $scope = $currentScope ?? (request()->route('scope') ?? 'default');

  $portalLabel = $currentPortalLabel
    ?? ($currentPortal['label'] ?? 'Amazing');

  // Evita erro no php artisan test quando não existe public/build/manifest.json
  try {
      $logoUrl = Vite::asset('resources/images/icon-vaapty.png');
  } catch (\Throwable $e) {
      $logoUrl = asset('images/icon-vaapty.png');
  }

  /**
   * Lojas disponíveis no seletor.
   * - Passe isso do backend (ideal).
   * - Formatos aceitos:
   *   ['loja_001', 'loja_002']
   *   ou
   *   [['key' => 'loja_001', 'label' => 'Loja 001'], ...]
   */
  $availableScopes = $availableScopes ?? [$scope];

  $normalizedScopes = [];
  foreach ($availableScopes as $opt) {
    if (is_array($opt)) {
      $key = $opt['key'] ?? ($opt['value'] ?? $scope);
      $label = $opt['label'] ?? $key;
    } else {
      $key = (string) $opt;
      $label = $key;
    }
    $normalizedScopes[] = ['key' => $key, 'label' => $label];
  }

  // Monta URLs mantendo a rota atual quando possível
  $routeName = \Illuminate\Support\Facades\Route::currentRouteName();
  $routeParams = request()->route()?->parameters() ?? [];

  $scopeOptions = [];
  foreach ($normalizedScopes as $opt) {
    $params = $routeParams;
    $params['scope'] = $opt['key'];

    $url = $routeName
      ? route($routeName, $params)
      : url("/s/{$opt['key']}");

    $scopeOptions[] = [
      'key' => $opt['key'],
      'label' => $opt['label'],
      'url' => $url,
    ];
  }

  // Quem pode trocar loja? (backend ideal).
  // Se não passar, assume que só pode trocar se tiver > 1 opção.
  $canSwitchScope = $canSwitchScope ?? (count($scopeOptions) > 1);

  // Contato da sua empresa (ideal passar via config/amazing.php)
  $supportPhone = $supportPhone ?? config('amazing.support_phone', null);
  $supportTel = $supportPhone ? 'tel:' . $supportPhone : '#';

  // URLs de ações (ajusta pras suas rotas quando tiver)
  $notificationsUrl = $notificationsUrl ?? '#';
  $accountUrl = $accountUrl ?? '#';
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

  {{-- Busca + criar --}}
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

  {{-- Ações do canto direito --}}
  <div class="app-topbar__actions">
    <span class="hidden md:inline app-topbar__portal">{{ $portalLabel }}</span>

    {{-- Loja / Scope selector --}}
    <div class="app-topbar__store" title="Loja atual">
      <svg class="app-topbar__icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3 9l2-5h14l2 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M5 9v11h14V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M9 20v-7h6v7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>

      <select
        class="app-topbar__storeSelect"
        @disabled(! $canSwitchScope || count($scopeOptions) <= 1)
        onchange="if(!this.disabled) window.location.href=this.value"
        aria-label="Selecionar loja"
      >
        @foreach ($scopeOptions as $opt)
          <option value="{{ $opt['url'] }}" @selected($opt['key'] === $scope)>
            {{ $opt['label'] }}
          </option>
        @endforeach
      </select>

      <svg class="app-topbar__chev" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    {{-- Telefone --}}
    <a
      href="{{ $supportTel }}"
      class="app-topbar__iconBtn"
      title="Contato"
      aria-label="Contato por telefone"
    >
      <svg class="app-topbar__icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M6 3h4l2 5-3 2c1.5 3 4 5.5 7 7l2-3 5 2v4c0 1-1 2-2 2C11 22 2 13 2 5c0-1 1-2 2-2h2z"
              stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
      </svg>
    </a>

    {{-- Notificações --}}
    <a
      href="{{ $notificationsUrl }}"
      class="app-topbar__iconBtn"
      title="Notificações"
      aria-label="Notificações"
    >
      <svg class="app-topbar__icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M18 8a6 6 0 10-12 0c0 7-3 7-3 7h18s-3 0-3-7"
              stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M9.5 19a2.5 2.5 0 005 0"
              stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </a>

    {{-- Conta --}}
    <a
      href="{{ $accountUrl }}"
      class="app-topbar__iconBtn"
      title="Minha conta"
      aria-label="Minha conta"
    >
      <svg class="app-topbar__icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M20 21a8 8 0 10-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
      </svg>
    </a>
  </div>
</header>
