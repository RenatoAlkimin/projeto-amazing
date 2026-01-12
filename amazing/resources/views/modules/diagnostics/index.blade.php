@extends('layouts.app')

@section('page_title', 'Diagnostics')
@section('page_subtitle', 'Inspeção rápida do estado (dev-only)')

@section('content')
  <div class="space-y-6">
    @include('partials.page-header')

    <section class="card p-6">
      <h2 class="text-lg card-title">Contexto atual</h2>
      <div class="mt-3 flex flex-wrap gap-2 text-sm">
        <span class="code-pill">Portal: <strong>{{ $portalLabel }}</strong> ({{ $portalId }})</span>
        <span class="code-pill">Scope: <strong>{{ $scope }}</strong></span>
        <span class="code-pill">Rota: <strong>{{ request()->route()?->getName() ?? 'n/a' }}</strong></span>
      </div>
    </section>

    <section class="card p-6">
      <h2 class="text-lg card-title">Vite</h2>
      <div class="mt-3 text-sm space-y-2 text-[hsl(var(--text))]">
        <div>
          Manifest: <code class="code-pill">{{ $vite['manifest_path'] }}</code>
          <span class="ml-2 font-semibold">{{ $vite['manifest_exists'] ? 'OK' : 'MISSING' }}</span>
        </div>
        <div>
          Hot file: <code class="code-pill">{{ $vite['hot_path'] }}</code>
          <span class="ml-2 font-semibold">{{ $vite['hot_exists'] ? 'ON' : 'OFF' }}</span>
        </div>
      </div>
    </section>

    <section class="card p-6">
      <h2 class="text-lg card-title">Portais → módulos efetivos</h2>

      <div class="mt-4 grid gap-3 md:grid-cols-2">
        @foreach ($portalMatrix as $pid => $p)
          <div class="tile block p-4">
            <div class="font-semibold">
              {{ $p['label'] }}
              <span class="ml-1 text-xs card-subtitle">({{ $pid }})</span>
            </div>
            <div class="mt-2 text-xs text-[hsl(var(--muted))] break-words">
              {{ implode(', ', $p['modules']) }}
            </div>
          </div>
        @endforeach
      </div>
    </section>

    <section class="card p-6">
      <h2 class="text-lg card-title">Rotas por módulo</h2>

      <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="text-left text-[hsl(var(--muted))]">
            <tr>
              <th class="py-2 pr-4 font-medium">Módulo</th>
              <th class="py-2 pr-4 font-medium">Rota</th>
              <th class="py-2 pr-4 font-medium">Registrada?</th>
              <th class="py-2 pr-4 font-medium">URL</th>
            </tr>
          </thead>

          <tbody class="align-top">
            @foreach ($moduleRoutes as $key => $m)
              <tr class="border-t border-[hsl(var(--border))]">
                <td class="py-2 pr-4 font-semibold">
                  {{ $m['label'] }}
                  <span class="ml-1 text-xs card-subtitle">({{ $key }})</span>
                </td>

                <td class="py-2 pr-4">
                  <code class="code-pill">{{ $m['route'] }}</code>
                </td>

                <td class="py-2 pr-4">
                  @if ($m['route_exists'])
                    <span class="font-semibold text-[hsl(var(--success))]">OK</span>
                  @else
                    <span class="font-semibold text-[hsl(var(--danger))]">NO</span>
                  @endif
                </td>

                <td class="py-2 pr-4">
                  @if ($m['url'])
                    <a class="underline text-[hsl(var(--primary))]" href="{{ $m['url'] }}">
                      {{ $m['url'] }}
                    </a>
                  @else
                    <span class="text-[hsl(var(--muted))]">—</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  </div>
@endsection
