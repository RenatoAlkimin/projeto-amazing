@php
  // Vem do middleware SetScope (view()->share('currentScope', ...))
  $scope  = $currentScope ?? 'default';

  // Vem do middleware ResolvePortal (view()->share('currentPortal', ...))
  $portal = $currentPortal ?? 'loja';

  $portalModules = config("portals.$portal.modules", []);
  $modules = config('modules', []);

  // Ordena se tiver "order" no config/modules.php
  uasort($modules, fn ($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));

  $portalAllows = fn (string $key) =>
      in_array('*', $portalModules, true) || in_array($key, $portalModules, true);
@endphp

<nav class="px-2 space-y-1">
  @foreach ($modules as $key => $mod)
    @continue(!$portalAllows($key))

    @php
      $routeName = $mod['route'] ?? null;
      $label     = $mod['label'] ?? ucfirst($key);
      $perm      = $mod['permission'] ?? null;

      // Evita quebrar se o config estiver apontando pra rota que não existe
      $hasRoute = $routeName && \Illuminate\Support\Facades\Route::has($routeName);

      // Permissão fina (quando tiver auth/perms de verdade)
      $permOk = !$perm || !auth()->check() || auth()->user()->can($perm);

      $isActive = request()->routeIs($key . '.*');
    @endphp

    @continue(!$hasRoute || !$permOk)

    <a
      href="{{ route($routeName, ['scope' => $scope]) }}"
      @class([
        'block rounded px-3 py-2 hover:bg-gray-100',
        'bg-gray-100' => $isActive,
      ])
      @if($isActive) aria-current="page" @endif
    >
      {{ $label }}
    </a>
  @endforeach
</nav>
