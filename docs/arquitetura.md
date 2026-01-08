# Amazing — Arquitetura e Decisões Técnicas (UI-only)

> **Documento vivo** do protótipo UI-only do Amazing (Hub Vaapty).  
> Objetivo: manter **navegação, UI e governança** consistentes enquanto o sistema evolui para a Fase 2 (auth/RBAC/DB/integrações).

**Arquivo canônico:** `docs/arquitetura.md`  
**Última atualização:** 08/01/2026  
**Status:** UI-only (Fase 1) • **Fonte de verdade:** este arquivo + `docs/adr/`  
**Escopo:** organização de rotas, portais, módulos, sidebar, front-end e convenções (sem regra de negócio).

---

## Sumário
1. Princípios (Fase 1 — UI-only)
2. Stack
3. Estrutura do repositório
4. Conceitos (Portal, Módulo, Scope)
5. Rotas e organização
6. Convenções de nomes (rotas)
7. Governança de acesso (Portal → Módulos) — macro
8. Contexto unificado (Portal e Scope)
9. Sidebar governada por config (sem hardcode)
10. Controllers e Views
11. Front-end (Vite + Tailwind v4 + Layout HubSpot-like)
12. UI / Design System (mínimo viável)
13. Ambiente local (UI-only) — recomendado
14. Testes (baratos e que evitam typo)
15. Como adicionar um novo módulo
16. Fase 2 (contratos sem implementar)
17. ADRs (referência)

---

## 1) Princípios (Fase 1 — UI-only)
- **UI-only de verdade:** sem DB, sem regra de negócio, sem integrações.
- **Estrutura pra crescer:** modularidade desde o começo (portais, módulos e scope).
- **Consistência acima de liberdade:** rotas previsíveis, layout único, sidebar governada por config.
- **Portal ≠ Módulo ≠ Permissão fina:** responsabilidades separadas para não virar spaghetti.
- **A mesma regra em um só lugar:** sidebar e middleware não podem divergir (fonte única).

**Invariantes (não quebrar):**
- Tudo “operacional” roda em `/s/{scope}`.
- A sidebar reflete exatamente o que o portal pode acessar.
- Acesso por URL direta é bloqueado quando o portal não permite.

---

## 2) Stack
- **Laravel 12 + Blade**
- **Tailwind CSS v4 (via Vite)**
  - usa `@import 'tailwindcss'`, `@source`, `@theme`, `@layer`, `@apply`
- **Vite + laravel-vite-plugin + @tailwindcss/vite**
- Dev local via **Herd** (`*.test`)

---

## 3) Estrutura do repositório
- `docs/` documentação
- `amazing/` app Laravel (código)

---

## 4) Conceitos

### 4.1 Portal
**Portal** representa o “tipo de painel” (macro-contexto):
- `amazing`, `franchising`, `franqueado`, `franqueado_central`, `loja`

O portal define:
- **home do painel** (rota de entrada)
- **macro-acesso** a módulos (ex.: loja não enxerga central)

**Fonte de verdade:** `amazing/config/portals.php`

### 4.2 Módulo (Module)
**Módulo** é um domínio funcional:
- `hub`, `comercial`, `financeiro`, `central`, ...

O módulo define:
- rotas do módulo (URLs e nomes)
- controllers/views do módulo
- (futuro) permissões finas dentro do módulo

**Catálogo (fonte de verdade):** `amazing/config/modules.php`

### 4.3 Scope (Escopo)
As rotas dos módulos são escopadas por `{scope}`:
- `/s/{scope}` (hub)
- `/s/{scope}/comercial`
- `/s/{scope}/financeiro`
- `/s/{scope}/central`

No UI-only, `scope` é um identificador (ex.: `default`).  
Na Fase 2 vira contexto real (loja, franqueado, regional etc).

---

## 5) Rotas e organização

### 5.1 Pastas e arquivos
```text
amazing/routes/
  web.php
  portals/
    amazing.php
    franchising.php
    franqueado.php
    franqueado_central.php
    loja.php
    scoped_modules.php
  modules/
    hub.php
    diagnostics.php
    comercial.php
    financeiro.php
    central.php
    marketing.php
    rh.php
```

> Nota: a pasta `routes/portals/` era chamada `routes/groups/`. Renomeamos para **portals** para deixar explícito que esses arquivos são as rotas de entrada por portal (e evitar confusão com “route groups” do Laravel). (Ver ADR0006)

### 5.2 `amazing/routes/web.php` (agregador)
- Importa os portais (rotas de entrada por painel)
- Importa os módulos escopados (onde o “trabalho” acontece)

### 5.3 `amazing/routes/portals/*.php` (portais)
- Define rotas de entrada por portal (ex.: `/loja`)
- No protótipo, o portal pode ser setado na sessão (modo debug)
- Recomendação: após setar o portal, **redirecionar para** `route('hub.index', ['scope' => 'default'])`

### 5.4 `amazing/routes/portals/scoped_modules.php`
- Define prefixo: `s/{scope}`
- Aplica middlewares:
  - `resolve_portal` (portal atual)
  - `set_scope` (scope atual)
  - *(por módulo)* `module_enabled:{módulo}` (`App\Http\Middleware\EnsureModuleEnabled`) — bloqueia acesso quando o módulo está desabilitado no config
- Importa as rotas dos módulos

---

## 6) Convenções de nomes (rotas)
- Entrada do módulo: `*.index`
- Exemplo:
  - `comercial.index`, `financeiro.index`, `central.index`, `hub.index`
- Convenção de rotas no arquivo do módulo:
  - `prefix('<modulo>')`
  - `as('<modulo>.')`
  - rota de entrada: `->name('index')`

---

## 7) Governança de acesso (Portal → Módulos) — macro

### 7.1 Fonte de verdade
- `amazing/config/portals.php`
  - `modules` lista o que o portal pode acessar
  - `'*'` significa **acesso total** (a todos os módulos do catálogo)
- `amazing/config/modules.php`
  - metadados: `label`, `route`, `order`, `section`, `icon`, `enabled` (feature flag), `permission` (futuro)

### 7.2 Enforcement (segurança)
- Middleware `module_enabled:<modulo>` em cada módulo
- Bloqueia acesso por URL direta quando o portal não permite (resposta 403)

> Regra de ouro: **a mesma lógica de allowlist/wildcard deve ser usada na sidebar e no middleware**.

### 7.3 Módulos internos (dev-only): `diagnostics`
Alguns módulos existem apenas para **verificação do protótipo** e não fazem parte do produto final.

Padrão recomendado:
- Módulo: `diagnostics` (`diagnostics.index` em `/s/{scope}/diagnostics`)
- Visível/apenas permitido no portal **`amazing`** (painel dev)
- **Feature flag** para habilitar/desabilitar:
  - `amazing/config/amazing.php` → `enable_diagnostics`
  - `.env` (local) → `AMAZING_ENABLE_DIAGNOSTICS=true`
- Quando desabilitado, deve responder **404** e não aparecer na sidebar.

Isso mantém o “painel dev” profissional (observabilidade e sanity checks) sem vazar para outros portais.


---

## 8) Contexto unificado (Portal e Scope)
Para evitar divergência de regras (e bugs chatos), o sistema usa **classes de contexto** como fonte única:

- `amazing/app/Support/Context/PortalContext.php`
  - resolve portal atual (app/session)
  - valida portais disponíveis
  - aplica wildcard/allowlist de módulos
  - expõe informações úteis (label, módulos permitidos)
- `amazing/app/Support/Context/ScopeContext.php`
  - resolve scope atual (rota/session)
  - validação básica (regex) no UI-only

### 8.1 Debug: alternar portal via query string
- **Somente em dev/local** e controlado por flag:
  - `amazing/config/amazing.php` → `allow_portal_query_switch`
  - `.env` local → `AMAZING_ALLOW_PORTAL_QUERY_SWITCH=true`
- Exemplo:
  - `/s/default?portal=franchising`

Em produção, isso deve ficar desabilitado.

---

## 9) Sidebar governada por config (sem hardcode)
A sidebar é renderizada a partir de:
- `amazing/config/portals.php` (módulos permitidos)
- `amazing/config/modules.php` (catálogo)

Builder:
- `amazing/app/Support/Navigation/SidebarBuilder.php`
  - ordena por `section` + `order`
  - gera URL via `route(<rota>, ['scope' => <scope>])`
  - marca ativo com `request()->routeIs('<modulo>.*')`
  - filtra módulos desabilitados por feature flag (ex.: `diagnostics`)
  - (futuro) filtra também por permissão fina (`can(...)`)

Composer:
- `amazing/app/View/Composers/SidebarComposer.php`

Registro (View Composer):
- `amazing/app/Providers/AppServiceProvider.php`
  - `View::composer('partials.sidebar', SidebarComposer::class);`

View:
- `amazing/resources/views/partials/sidebar.blade.php`

---

## 10) Controllers e Views
- Controllers: `amazing/app/Http/Controllers/<Modulo>/...`
- Views: `amazing/resources/views/modules/<modulo>/...`
- Layout base: `amazing/resources/views/layouts/app.blade.php`

**Regra Fase 1:**
- Controller monta **dados fake** e escolhe view
- Sem regra de negócio (se começar a “pensar demais”, você saiu da Fase 1 😄)

**Regra importante (front):**
- Views de módulos **NÃO** devem ter `<!doctype html>`, `<head>`, `<body>`.
- Devem usar:
  - `@extends('layouts.app')`
  - `@section('content')`

Isso garante:
- carregamento de CSS/JS via Vite
- sidebar/topbar consistentes
- navegação uniforme

---

## 11) Front-end (Vite + Tailwind v4 + Layout HubSpot-like)

### 11.1 Objetivo
Ter um front-end “SaaS” consistente (layout único + componentes) e com fricção baixa em dev (hot reload), sem virar um framework SPA na Fase 1.

### 11.2 Vite (fonte de verdade)
- Config:
  - `amazing/vite.config.js`
- Entrypoints:
  - `amazing/resources/css/app.css`
  - `amazing/resources/js/app.js`

No `vite.config.js`, plugins:
- `laravel-vite-plugin` (integra com Blade)
- `@tailwindcss/vite` (Tailwind v4)

### 11.3 Tailwind v4 (como funciona aqui)
O Tailwind v4 usa:
- `@import "tailwindcss";`
- `@source` para scan de templates
- `@theme` para tokens (se aplicável)
- `@layer` + `@apply` para componentes utilitários

Arquivo principal:
- `amazing/resources/css/app.css` (**manifest**)

### 11.4 Organização de CSS (profissional e simples)
Estrutura recomendada:

```text
amazing/resources/css/
  app.css
  theme.css
  base.css
  layouts/
    shell.css
    header.css
    sidebar.css
  components/
    buttons.css
    cards.css
    forms.css
    badges.css
  pages/ (opcional)
```

Regras:
- Tailwind no Blade para layout/spacing rápido quando fizer sentido.
- `components/` para padrões reutilizáveis (`.btn`, `.card`, etc.).
- `layouts/` para estrutura global (shell/topbar/sidebar).
- `pages/` só quando inevitável.

### 11.5 Layout conectado (HubSpot-like): Chrome + Surface

#### 11.5.1 Conceito
O layout segue o padrão **Chrome + Surface**:
- **Chrome**: faixa “do app” (Topbar + Sidebar) com cor sólida.
- **Surface**: área de conteúdo clara, com **curva no canto superior esquerdo** e **canto superior direito reto**.

#### 11.5.2 Arquivos fonte de verdade (layout)
Blade:
- `amazing/resources/views/layouts/app.blade.php` (layout global)
- `amazing/resources/views/partials/topbar.blade.php`
- `amazing/resources/views/partials/sidebar.blade.php`

CSS:
- `amazing/resources/css/layouts/shell.css` (tokens + chrome + surface)
- `amazing/resources/css/layouts/header.css` (topbar)
- `amazing/resources/css/layouts/sidebar.css` (sidebar)

**Regra:** `app.css` deve importar `shell.css`, `header.css` e `sidebar.css`.

#### 11.5.3 Tokens (tema/medidas)
Os tokens do layout ficam em `layouts/shell.css` como CSS variables (ex.: `--hs-rail`, `--hs-topbar`, `--hs-radius`).

Tema atual:
- Chrome (topbar + sidebar): `#48186e`
  - `--hs-chrome: #48186e;`
  - `--hs-top: #48186e;`

Geometria importante:
- `app-surface` tem **curva só no canto superior esquerdo**:
  - `border-top-left-radius: var(--hs-radius);`
  - `border-top-right-radius: 0;`

#### 11.5.4 “Contrato” de classes do layout
Estas classes são o contrato do layout e não devem ser renomeadas sem revisão do CSS:

- `app-shell`: fundo do app (chrome)
- `app-frame`: estrutura vertical (topbar + body)
- `app-body`: linha (sidebar + conteúdo)
- `app-surface`: superfície do conteúdo (curva só no canto superior esquerdo)
- `app-contentHeader`: header interno sticky dentro do conteúdo

Skeleton esperado no Blade:

```blade
<body class="app-shell">
  <div class="app-frame">
    @include('partials.topbar')

    <div class="app-body">
      @include('partials.sidebar')

      <main class="app-surface">
        <header class="app-contentHeader">...</header>
        <div class="p-6">@yield('content')</div>
      </main>
    </div>
  </div>
</body>
```

Observações importantes:
- Não aplicar `bg-white` / `bg-gray-50` no `<main>` (o fundo do conteúdo é responsabilidade da `app-surface`).
- A topbar não deve ter sombra/borda inferior (“risco”) — o visual atual é chrome sólido.

#### 11.5.5 Topbar (padrão)
- Estrutura usa um slot fixo alinhado com o rail:
  - `.app-topbar__slot` com largura do rail (`--hs-rail`)
- Search no estilo HubSpot:
  - input pill + ícone + `kbd` “Ctrl K” (visual)
- Botão “+” circular (visual)

> (Opcional) Comportamento de `Ctrl+K` pode ser implementado no JS depois; por enquanto é apenas UI.

#### 11.5.6 Sidebar (padrão)
- Sidebar “rail” fixa (largura = `--hs-rail`)
- Hover e active “soft” (sem branco estourado)
- Tooltip no hover
- Rodapé pode exibir badge “Beta” quando aplicável

### 11.6 Branding (logo)
- Quando você quer versionamento/hash do asset, mantenha em `resources/images` e referencie via `Vite::asset(...)`.
- Em ambientes onde o *build* do front não roda (ex.: máquina limpa / testes sem `npm run build`), chamadas ao Vite podem falhar por falta do `public/build/manifest.json`.

**Padrão recomendado**
1) Tenha um fallback do logo em `public/images/icon-vaapty.png` (ou equivalente).  
2) No Blade, escolha entre Vite e fallback com base na existência do manifest:

```blade
@php
  $logo = file_exists(public_path('build/manifest.json'))
    ? Vite::asset('resources/images/icon-vaapty.png')
    : asset('images/icon-vaapty.png');
@endphp

<img src="{{ $logo }}" alt="Vaapty" class="app-topbar__logo" />
```

### 11.7 Ícones (componente Blade)
Para ícones do rail da sidebar:
- `amazing/resources/views/components/icon.blade.php`
- Uso:
  - `<x-icon name="grid" class="w-5 h-5" />`

Os ícones são dirigidos por:
- `amazing/config/modules.php` → campo `icon`

### 11.8 Carregamento do CSS/JS (regra de ouro)
No `<head>` do layout:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Regra do projeto**
- Em **dev**, manter `@vite(...)` (para hot reload e consistência de UI).
- Em **CI**, o pipeline deve garantir `npm ci && npm run build` antes de `php artisan test` (ver 14.4).

> Se você quiser rodar testes sem build do front (modo rápido/local), documentamos a alternativa em 14.4 — mas isso não é o padrão recomendado para CI.

### 11.9 Dev vs Build (muito importante)
Em desenvolvimento (hot reload):

```bash
cd amazing
npm install
npm run dev
```

Em modo estático (sem dev server):

```bash
cd amazing
npm run build
```

Atenção: se existir `public/hot`, o Laravel tentará carregar assets do dev server.  
Se você rodar build e quiser modo estático, remova o hot:

- macOS/Linux/Git Bash:
  ```bash
  rm public/hot
  ```
- PowerShell:
  ```powershell
  Remove-Item public/hot -ErrorAction SilentlyContinue
  ```
- CMD:
  ```bat
  del public\hot
  ```

### 11.10 VS Code (qualidade de vida)
Tailwind v4 usa at-rules que o linter CSS do VS Code pode marcar como “Unknown at rule”.  
Isso não quebra o build — é só diagnóstico do editor.

Sugestão local:
- instalar Tailwind CSS IntelliSense
- opcional: `.vscode/settings.json` com `css.lint.unknownAtRules = "ignore"`

---

## 12) UI / Design System (mínimo viável)

### Estratégia
- Tailwind para composição rápida
- Componentes Blade para reutilização (quando repetiu 2x, vira componente)

### Locais sugeridos
- `amazing/resources/views/components/` (ex.: `icon`)
- `amazing/resources/views/components/ui/` (quando crescer)

### Componentes recomendados
- `ui/button`, `ui/card`, `ui/badge`
- `ui/page-header` (título + ações + breadcrumbs opcional)
- `ui/table` (toolbar + paginação fake)
- `ui/empty-state` (vai aparecer muito)

**Regra prática:** se repetiu 2x (com pequenas variações), vira componente.

---

## 13) Ambiente local (UI-only) — recomendado
Para reduzir fricção no protótipo:

- `APP_URL=http://amazing.test`
- `APP_LOCALE=pt_BR` / `APP_FALLBACK_LOCALE=pt_BR` / `APP_FAKER_LOCALE=pt_BR`
- `QUEUE_CONNECTION=sync` (sem worker)
- `CACHE_STORE=file` (sem tabela de cache)
- `AMAZING_ALLOW_PORTAL_QUERY_SWITCH=true` (apenas local)
- `AMAZING_ENABLE_DIAGNOSTICS=true` (opcional/local — habilita o módulo Diagnostics)

Front-end:
- `npm run dev` durante desenvolvimento
- `npm run build` para modo estático

Observação: `QUEUE_CONNECTION=database` e `CACHE_STORE=database` exigem tabelas/migrations e tendem a gerar “erro do nada” no UI-only.

---

## 14) Testes

### 14.1 Executar a suíte
Comandos usuais:

```bash
php artisan test
```

Quando você mexer em Blade e aparecer comportamento “estranho” de cache/compilação (principalmente em Windows), rode:

```bash
php artisan view:clear
```

### 14.1.1 Sanity check rápido (PowerShell)
Quando você quiser garantir que “tudo está de pé” (config/rotas/views/testes) em uma máquina limpa:

```powershell
php artisan optimize:clear
php artisan route:list --path=s
php artisan test
```

### 14.2 `Tests\Feature\ConfigIntegrityTest`
Esse teste garante que a “arquitetura por configuração” não ficou inconsistente.

Coberturas típicas:
- Portais referenciam apenas módulos existentes (ex.: `config/portals.php` → `modules`).
- Cada módulo configurado tem **rota base** e a rota está registrada.
- Cada módulo configurado possui **arquivo de rotas** — evita “módulo aparece no menu mas não tem rotas”.

### 14.3 `Tests\Feature\ExampleTest`
O `ExampleTest` valida que a rota raiz (`GET /`) responde com sucesso (200) seguindo redirects.

Para manter esse teste estável:
- Garanta que exista um comportamento definido para `/` (normalmente redirect para o Hub em `default`):
  - `return redirect()->route('hub.index', ['scope' => 'default']);`
- Evite que o layout/quaisquer parciais quebrem em ambiente de teste por dependências de front-end.

### 14.4 Vite em ambiente de teste (manifest)
Erros do tipo **“Vite manifest not found at: public/build/manifest.json”** acontecem quando o front não foi compilado e algum Blade tenta resolver assets via Vite.

**Padrão recomendado (CI) — compilar o front antes dos testes**
- `npm ci`
- `npm run build`
- `php artisan test`

**Alternativa (rápido/local) — testes sem build do front**
- Garanta fallback para assets (ex.: logo em `public/images`) e evite chamar `Vite::asset(...)` quando não houver manifest.
- Se ainda assim precisar, você pode evitar renderizar as tags do Vite em testes:
  - `@if (! app()->runningUnitTests()) @vite([...]) @endif`

> Regra: **CI deve usar o padrão recomendado**. A alternativa existe só para reduzir fricção local quando Node não está disponível.

### 14.5 `Tests\Feature\PortalModuleAccessTest` (governança por portal + Diagnostics)
Esse teste é um “smoke test” barato para garantir que **portal → módulos → sidebar** não divergiram.

Ele valida:
- **Matriz de acesso por portal**: para cada portal, cada módulo configurado deve responder:
  - `200` quando o portal permite (allowlist ou `'*'`)
  - `403` quando o portal não permite (enforced pelo middleware `module_enabled:<modulo>`)
- **Diagnostics desabilitado = 404**: quando `amazing.enable_diagnostics=false`, a rota `diagnostics.index` deve retornar `404` (o módulo “some do mapa”).
- **Sidebar consistente**: quando habilitado, o item “Diagnostics” aparece **somente** no portal `amazing`.

Regras práticas para manter estável:
- O teste costuma habilitar temporariamente `amazing.allow_portal_query_switch=true` para conseguir alternar portal via `?portal=...` durante o request.
- Para o “Diagnostics desabilitado = 404”, o gate deve acontecer **antes** do check de portal (ex.: middleware/route guard dedicado), para evitar `403` quando o módulo está desligado.

---

## 15) Como adicionar um novo módulo

### 15.1 Checklist (passo a passo)
1) **Adicionar no catálogo de módulos**
   - `amazing/config/modules.php`
   - Defina pelo menos:
     - `label` (nome no menu)
     - `route` (ex.: `marketing.index`)
     - `section` (agrupamento na sidebar)
     - `order` (ordem na seção)
     - `icon` (ex.: `grid`, `chart`, etc.)

2) **Criar arquivo de rotas do módulo**
   - `amazing/routes/modules/<modulo>.php`
   - Padrão:
     - `prefix('<modulo>')`
     - `as('<modulo>.')`
     - rota de entrada com `->name('index')`

3) **Garantir que o agregador importe o arquivo**
   - Se o seu `scoped_modules.php` lista os módulos manualmente, inclua o novo arquivo lá.
   - Se ele já faz import dinâmico (por config), apenas garanta que o arquivo exista com o nome esperado.

4) **Criar controller e view**
   - Controller: `amazing/app/Http/Controllers/<Modulo>/<Modulo>Controller.php` (ou equivalente)
   - View: `amazing/resources/views/modules/<modulo>/index.blade.php`
   - A view deve **estender** `layouts.app` e renderizar conteúdo dentro de `@section('content')`.

5) **Liberar o módulo no(s) portal(is)**
   - `amazing/config/portals.php`
   - Adicione o módulo na lista do portal **ou** use `'*'` para acesso total (quando fizer sentido).


> Nota (módulo interno / dev-only): se for um módulo “de diagnóstico” (ex.: `diagnostics`), restrinja-o ao portal `amazing` e coloque uma feature flag para poder desligar completamente (retornando 404 e removendo da sidebar).

6) **Rodar testes**
   - `php artisan test`
   - Se mexeu em Blade e houver cache estranho: `php artisan view:clear`

### 15.2 Resultado esperado
- O módulo aparece automaticamente na sidebar (por config).
- URL direta do módulo:
  - funciona quando o portal permite,
  - retorna 403 quando o portal não permite (enforced pelo middleware).

---

## 16) Fase 2 (contratos sem implementar)
A Fase 1 é UI-only. A Fase 2 introduz “sistema de verdade” (auth, RBAC, DB, integrações) **sem quebrar os contratos de navegação e governança**.

### 16.1 Auth e RBAC
- Middleware de autenticação (`auth`) na árvore `/s/{scope}`.
- Permissão fina por módulo e por ação:
  - `modules.php` já prevê `permission` (futuro).
  - Sidebar filtra por `can(...)` além do filtro por portal.
  - Rotas/controllers aplicam `can(...)`/policies/gates.

### 16.2 Scope real (multi-tenant / contexto)
- `scope` passa a representar entidade real (loja/franqueado/regional).
- `ScopeContext` deve:
  - validar existência (DB),
  - carregar metadados (nome, tipo, hierarquia),
  - controlar a troca de escopo (quando aplicável).

### 16.3 Dados e integração sem “vazar” para UI
- Controllers deixam de gerar dados fake e passam a consumir:
  - services (domínio),
  - repositórios (persistência),
  - integrações (APIs internas/externas),
  mantendo views “burras” (apenas render).

### 16.4 Observabilidade e auditoria
- Logs estruturados por `portal` + `scope`.
- Eventos/auditoria para ações relevantes (quando existir regra de negócio).

---

## 17) ADRs (referência)
As decisões “de verdade” devem morar em **ADRs**, não espalhadas em comentários ou commits.

### 17.1 Local e convenção
- Pasta: `docs/adr/`
- Nome sugerido: `ADR0006-titulo-curto.md` (ou `ADR-0006-titulo-curto.md`, desde que consistente)
- Este arquivo (`docs/arquitetura.md`) é o **mapa geral**; os ADRs são a **fonte de decisão**.

### 17.2 Template
Use o template padrão do time:

- Status: `Proposto | Aceito | Rejeitado | Substituído`
- Contexto → Decisão → Consequências → Referências

### 17.3 Relação com este documento
- Se uma seção aqui ficar “opinativa”, transforme em ADR e coloque um link em **Referências**.
- Quando uma decisão mudar, **não reescreva história**:
  - Crie um novo ADR “Substitui ADRXXXX” e atualize os links.

---

*Fim do documento.*
