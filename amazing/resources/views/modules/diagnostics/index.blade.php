@extends('layouts.app')

@section('page_title', 'Diagnostics')
@section('page_subtitle', 'Inspeção rápida do estado (dev-only)')

@section('content')
  <div class="space-y-6">
    <div class="rounded-xl border bg-white p-4">
      <div class="text-sm text-gray-500">Contexto atual</div>
      <div class="mt-2 flex flex-wrap gap-2 text-sm">
        <span class="rounded bg-gray-100 px-2 py-1">Portal: <strong>{{ $portalLabel }}</strong> ({{ $portalId }})</span>
        <span class="rounded bg-gray-100 px-2 py-1">Scope: <strong>{{ $scope }}</strong></span>
        <span class="rounded bg-gray-100 px-2 py-1">Rota: <strong>{{ request()->route()?->getName() ?? 'n/a' }}</strong></span>
      </div>
    </div>

    <div class="rounded-xl border bg-white p-4">
      <div class="text-sm text-gray-500">Vite</div>
      <div class="mt-3 text-sm space-y-2">
        <div>Manifest: <code>{{ $vite['manifest_path'] }}</code> — <strong>{{ $vite['manifest_exists'] ? 'OK' : 'MISSING' }}</strong></div>
        <div>Hot file: <code>{{ $vite['hot_path'] }}</code> — <strong>{{ $vite['hot_exists'] ? 'ON' : 'OFF' }}</strong></div>
      </div>
    </div>

    <div class="rounded-xl border bg-white p-4">
      <div class="text-sm text-gray-500">Portais → módulos efetivos</div>
      <div class="mt-3 grid gap-3 md:grid-cols-2">
        @foreach ($portalMatrix as $pid => $p)
          <div class="rounded-lg border p-3">
            <div class="font-medium">{{ $p['label'] }} <span class="text-xs text-gray-500">({{ $pid }})</span></div>
            <div class="mt-2 text-xs text-gray-600 break-words">
              {{ implode(', ', $p['modules']) }}
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="rounded-xl border bg-white p-4">
      <div class="text-sm text-gray-500">Rotas por módulo</div>

      <div class="mt-3 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="text-left text-gray-500">
            <tr>
              <th class="py-2 pr-4">Módulo</th>
              <th class="py-2 pr-4">Rota</th>
              <th class="py-2 pr-4">Registrada?</th>
              <th class="py-2 pr-4">URL</th>
            </tr>
          </thead>
          <tbody class="align-top">
            @foreach ($moduleRoutes as $key => $m)
              <tr class="border-t">
                <td class="py-2 pr-4 font-medium">{{ $m['label'] }} <span class="text-xs text-gray-500">({{ $key }})</span></td>
                <td class="py-2 pr-4 font-mono">{{ $m['route'] }}</td>
                <td class="py-2 pr-4">
                  {!! $m['route_exists'] ? '<span class="text-green-700">OK</span>' : '<span class="text-red-700">NO</span>' !!}
                </td>
                <td class="py-2 pr-4">
                  @if ($m['url'])
                    <a class="text-indigo-700 underline" href="{{ $m['url'] }}">{{ $m['url'] }}</a>
                  @else
                    <span class="text-gray-400">—</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
@endsection
