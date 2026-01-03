# ADR 0004 — Front-end via Vite + Tailwind v4 (layout único + partials)

- **Status:** Aceito
- **Data:** 03/01/2026
- **Contexto:** Fase 1 (UI-only)

## Contexto
O protótipo UI-only do Amazing precisa:
- ter **layout consistente** em todas as telas (sidebar/topbar, espaçamentos, tipografia)
- permitir evolução rápida da UI sem virar SPA (sem React/Vue por enquanto)
- manter **fricção baixa** no desenvolvimento (hot reload, build simples)
- evitar divergência de UI por view “solta” com `<html>/<head>/<body>` próprio

## Decisão
Adotar **Vite + Tailwind CSS v4** no Laravel (Blade), com:
1) **Layout único global**
   - `resources/views/layouts/app.blade.php`
   - responsável por carregar assets via Vite e compor o shell (topbar + sidebar + conteúdo)

2) **Partials para estrutura**
   - `resources/views/partials/topbar.blade.php`
   - `resources/views/partials/sidebar.blade.php`
   - o layout inclui esses partials para garantir consistência

3) **Módulos usam o layout**
   - Views de módulos **devem** usar:
     - `@extends('layouts.app')`
     - `@section('content')`
   - Views de módulos **não** devem conter `<!doctype html>`, `<head>`, `<body>`

4) **Tailwind v4 via plugin oficial do Vite**
   - Tailwind v4 é processado via `@tailwindcss/vite`
   - `resources/css/app.css` usa o padrão v4:
     - `@import 'tailwindcss'`
     - `@source` (scan)
     - `@theme` (tokens)
     - `@layer` + `@apply` (componentes utilitários)

5) **Entrypoints e build**
   - Entrypoints:
     - `resources/css/app.css`
     - `resources/js/app.js`
   - Vite config:
     - `vite.config.js` com `laravel-vite-plugin` + `@tailwindcss/vite`

## Motivação (por que essa escolha)
- **Velocidade**: Tailwind acelera prototipagem e mantém consistência.
- **Organização**: layout único + partials evitam “telas com cara diferente”.
- **Menos risco**: não introduz SPA e roteamento duplicado.
- **Compatível com Fase 2**: mantém base sólida para auth/RBAC e componentes mais complexos.

## Consequências
### Positivas
- UI consistente e fácil de evoluir
- Hot reload em dev (Vite)
- CSS organizado por “manifest + módulos” (`layouts/`, `components/`)
- Menos bugs de “CSS não aplica” quando views usam o layout corretamente

### Negativas / Trade-offs
- Em dev, normalmente é necessário manter `npm run dev` rodando
- Warnings de editor (VS Code) para `@apply/@source/@theme` podem aparecer sem extensão/config (não afeta build)

## Implementação (referência)
- `resources/views/layouts/app.blade.php`
  - deve conter **sem condicional**:
    - `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- `resources/views/partials/topbar.blade.php`
- `resources/views/partials/sidebar.blade.php`
- `resources/css/app.css` (manifest Tailwind v4)
- `resources/css/layouts/header.css`
- `resources/css/layouts/sidebar.css`
- `vite.config.js` com:
  - `laravel-vite-plugin`
  - `@tailwindcss/vite`

## Operação (dev vs build)
### Dev (hot reload)
```bash
cd amazing
npm install
npm run dev
