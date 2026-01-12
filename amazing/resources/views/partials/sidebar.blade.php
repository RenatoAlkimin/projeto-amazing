@php
  $sections = $sidebarSections ?? [];
@endphp

<aside class="app-sidebar" aria-label="Sidebar">
  <nav class="app-sidebar__nav" aria-label="Navegação">
    @foreach ($sections as $section)
      @if (!$loop->first)
        <div class="app-sidebar__divider" role="separator"></div>
      @endif

      @foreach (($section['items'] ?? []) as $item)
        @php($active = (bool) ($item['active'] ?? false))

        <a
          href="{{ $item['url'] }}"
          title="{{ $item['label'] }}"
          class="app-sidebar__item {{ $active ? 'is-active' : '' }}"
          @if($active) aria-current="page" @endif
        >
          <x-icon :name="($item['icon'] ?? null)" class="w-5 h-5" />
          <span class="sr-only">{{ $item['label'] }}</span>
        </a>
      @endforeach
    @endforeach
  </nav>

  <div class="app-sidebar__bottom">
    <span class="app-sidebar__beta">Beta</span>
  </div>
</aside>
