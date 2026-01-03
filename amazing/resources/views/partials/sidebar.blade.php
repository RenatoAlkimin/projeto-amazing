@php
  $sections = $sidebarSections ?? [];

  $scope = $currentScope ?? (request()->route('scope') ?? 'default');

  // compat: se ainda existir $currentPortal (array)
  $portalLabel = $currentPortalLabel
    ?? ($currentPortal['label'] ?? 'Amazing');
@endphp

<aside class="app-sidebar">
  <nav class="app-sidebar__nav" aria-label="Navegação">
    @foreach ($sections as $section)
      @if (!$loop->first)
        <div class="app-sidebar__divider" role="separator"></div>
      @endif

      @foreach (($section['items'] ?? []) as $item)
        @php($active = (bool) ($item['active'] ?? false))

        <a
          href="{{ $item['url'] }}"
          class="app-sidebar__item {{ $active ? 'is-active' : '' }}"
          aria-current="{{ $active ? 'page' : 'false' }}"
        >
          <x-icon :name="($item['icon'] ?? null)" class="w-5 h-5" />
          <span class="sr-only">{{ $item['label'] }}</span>

          <span class="app-sidebar__tooltip">
            {{ $item['label'] }}
          </span>
        </a>
      @endforeach
    @endforeach
  </nav>

  <div class="app-sidebar__bottom">
    <div class="app-sidebar__footer">
      {{ $portalLabel }} • {{ $scope }}
    </div>
  </div>
</aside>
