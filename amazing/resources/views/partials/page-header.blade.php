@php
  // Params (via @include)
  $title = $title ?? null;
  $subtitle = $subtitle ?? null;
  $showScope = $showScope ?? false;
  $scope = $scope ?? null;

  // Opcional: HTML para ações do lado direito (botões/links)
  // Ex: ['actions' => '<a class="..." href="...">Novo</a>']
  $actions = $actions ?? null;

  // Opcional: classe extra no wrapper
  $class = $class ?? '';

  $resolvedTitle = $title ?? trim($__env->yieldContent('page_title', ''));
  $resolvedSubtitle = $subtitle ?? (trim($__env->yieldContent('page_subtitle')) ?: null);

  $resolvedScope = $scope
    ?? (request()->route('scope') ?? config('amazing.default_scope', 'default'));
@endphp

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between {{ $class }}">
  <div class="min-w-0">
    @if($resolvedTitle !== '')
      <h1 class="text-2xl font-semibold tracking-tight text-[hsl(var(--text))]">
        {{ $resolvedTitle }}
      </h1>
    @endif

    @if($resolvedSubtitle)
      <p class="mt-1 text-sm card-subtitle">
        {{ $resolvedSubtitle }}
      </p>
    @endif
  </div>

  <div class="flex items-center gap-2">
    @if(!empty($actions))
      <div class="flex items-center gap-2">
        {!! $actions !!}
      </div>
    @endif

    
  </div>
</div>
