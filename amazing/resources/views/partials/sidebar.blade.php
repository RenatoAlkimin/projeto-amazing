<aside class="w-64 bg-white border-r">
  <div class="p-4 font-semibold">
    Amazing • Vaapty
    <div class="text-xs text-gray-500 mt-1">
      Portal: {{ $currentPortal ?? '-' }} • Scope: {{ $currentScope ?? '-' }}
    </div>
  </div>

  @php
    $grouped = collect($sidebarItems ?? [])->groupBy('section');
  @endphp

  <nav class="px-2 space-y-3">
    @foreach($grouped as $section => $items)
      <div>
        <div class="px-3 text-xs font-semibold uppercase text-gray-400 mb-1">
          {{ $section }}
        </div>

        <div class="space-y-1">
          @foreach($items as $item)
            <a class="block rounded px-3 py-2 hover:bg-gray-100 {{ $item['active'] ? 'bg-gray-100' : '' }}"
               href="{{ $item['href'] }}">
              {{ $item['label'] }}
            </a>
          @endforeach
        </div>
      </div>
    @endforeach
  </nav>
</aside>
