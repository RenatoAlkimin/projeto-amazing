<aside class="w-64 bg-white border-r">
  <div class="p-4 font-semibold">Amazing • Vaapty</div>

  <nav class="px-2 space-y-1">
    @foreach($sidebarItems ?? [] as $item)
      <a class="block rounded px-3 py-2 hover:bg-gray-100 {{ $item['active'] ? 'bg-gray-100' : '' }}"
         href="{{ $item['href'] }}">
        {{ $item['label'] }}
      </a>
    @endforeach
  </nav>
</aside>
