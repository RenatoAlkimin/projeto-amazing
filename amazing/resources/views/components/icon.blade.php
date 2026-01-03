@props([
  'name' => null,
  'class' => 'w-5 h-5',
])

@php
  $name = (string) ($name ?? '');
@endphp

@if ($name === 'grid')
  <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/>
  </svg>

@elseif ($name === 'briefcase')
  <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6V5a2 2 0 012-2h2a2 2 0 012 2v1"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8"/>
  </svg>

@elseif ($name === 'wallet')
  <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h16a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M17 7V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12h-6a2 2 0 000 4h6v-4z"/>
  </svg>

@elseif ($name === 'megaphone')
  <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 11v2a2 2 0 002 2h2l6 3V6L7 9H5a2 2 0 00-2 2z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M13 6h4a3 3 0 013 3v6a3 3 0 01-3 3h-4V6z"/>
  </svg>

@elseif ($name === 'users')
  <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11a4 4 0 10-8 0 4 4 0 008 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21a8 8 0 0116 0"/>
  </svg>

@else
  {{-- fallback --}}
  <span class="{{ $class }} inline-block rounded-full bg-slate-600/70"></span>
@endif
