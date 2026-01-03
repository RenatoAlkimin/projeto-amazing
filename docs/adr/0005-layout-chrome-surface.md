# ADR 0005 — Layout Chrome + Surface (HubSpot-like) + Tokens em `shell.css`

**Status:** Aceito  
**Data:** 03/01/2026  
**Contexto:** UI-only (Fase 1) • Front-end (Vite + Tailwind v4)

---

## Contexto
O protótipo UI-only precisava de um layout **SaaS consistente** (todas as telas “encaixam” no mesmo chrome), com sensação visual próxima ao HubSpot, sem virar SPA e sem espalhar estilos soltos em cada view.

Problemas que queríamos evitar:
- Divergência visual entre páginas (cada módulo inventando seu header/spacing).
- Estilos “colados” no Blade que ficam difíceis de manter.
- Dependência de biblioteca/tema externo pesado só para layout.
- Bordas/sombras inconsistentes entre Topbar/Sidebar (“risquinhos” aparecendo).

---

## Decisão
Adotamos o padrão **Chrome + Surface** como layout global:

- **Chrome**: a “casca” do app (Topbar + Sidebar) com cor sólida.
- **Surface**: área de conteúdo clara e elevada, com **curva somente no canto superior esquerdo** (canto superior direito reto).

Além disso:
1. Centralizamos as **medidas e tema** do layout em **CSS variables** (tokens) no arquivo `resources/css/layouts/shell.css`.
2. Definimos um **contrato de classes** (não renomear sem atualizar CSS):
   - `app-shell`, `app-frame`, `app-body`, `app-surface`, `app-contentHeader`
3. Padronizamos a topbar no estilo HubSpot:
   - search “pill” com ícone e `kbd` “Ctrl K” (visual)
   - botão “+” circular (visual)
   - **sem** borda/sombra inferior (“risco”)
4. O tema do chrome (topbar + sidebar) foi fixado para **#48186e**.

---

## Motivação / Racional
- **Consistência**: toda página herda o mesmo layout via `layouts/app.blade.php`.
- **Manutenção**: tokens em um lugar só (shell.css) reduzem ajuste “na unha”.
- **Escalabilidade**: novos módulos entram sem esforço e sem risco de “quebrar o visual”.
- **Aderência visual**: aproxima o protótipo do padrão HubSpot sem copiar framework/HTML deles.

---

## Consequências
### Positivas
- Layout único e previsível em toda a aplicação.
- Mudança de tema/medidas via tokens (CSS variables) é rápida.
- Sidebar + topbar ficam visualmente conectadas (chrome contínuo).
- `app-surface` controla o “fundo do conteúdo” (sem `bg-white`/`bg-gray-50` espalhado).

### Negativas / Trade-offs
- Exige disciplina: **não** renomear as classes do contrato sem atualizar CSS.
- Tokens são “simples”, não um design system completo (isso é intencional na Fase 1).
- `Ctrl+K` e ações do botão “+” são **apenas UI** por enquanto (comportamento fica para depois).

---

## Implementação (fonte de verdade)
### Blade
- `resources/views/layouts/app.blade.php` (layout global; aplica `app-shell/app-frame/app-body/app-surface`)
- `resources/views/partials/topbar.blade.php` (topbar HubSpot-like + branding)
- `resources/views/partials/sidebar.blade.php` (sidebar rail governada por config)

### CSS
- `resources/css/layouts/shell.css` (tokens + chrome + surface + geometria)
- `resources/css/layouts/header.css` (topbar)
- `resources/css/layouts/sidebar.css` (sidebar)

### Assets
- `resources/images/icon-vaapty.png` (logo no canto superior esquerdo)

---

## Detalhes de tokens (mínimo)
Os tokens **devem** ficar em `shell.css` (não espalhar em vários lugares):
- `--hs-rail` (largura do rail)
- `--hs-topbar` (altura da topbar)
- `--hs-radius` (raio do surface)
- `--hs-chrome` e `--hs-top` (cor do chrome / topbar)

Tema atual:
- `--hs-chrome: #48186e;`
- `--hs-top: #48186e;`

Geometria:
- `app-surface`:
  - `border-top-left-radius: var(--hs-radius);`
  - `border-top-right-radius: 0;`

---

## Alternativas consideradas
1. **Tailwind puro inline no Blade (sem classes/layout CSS)**
   - Pró: rápido no começo
   - Contra: vira bagunça e diverge por módulo

2. **Template/tema externo completo**
   - Pró: visual pronto
   - Contra: dependência pesada e difícil de adaptar ao nosso “Portal/Módulo/Scope”

3. **SPA (React/Vue)**
   - Pró: interação rica
   - Contra: escopo e fricção desnecessários na Fase 1 (UI-only)

---

## Regras de ouro (para não quebrar)
- Views de módulos devem usar `@extends('layouts.app')`.
- Não aplicar `bg-*` no `<main>`: o fundo é responsabilidade da `app-surface`.
- Não adicionar borda/sombra na topbar (evitar o “risco”).
- Tokens e geometria vivem em `shell.css` (fonte única).

---
