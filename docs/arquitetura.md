# Amazing — Arquitetura e Decisões Técnicas (UI-only)

> **Documento vivo** do protótipo UI-only do Amazing (Hub Vaapty).  
> Objetivo: manter **navegação, UI e governança** consistentes enquanto o sistema evolui para a Fase 2 (auth/RBAC/DB/integrações).

**Arquivo canônico:** `docs/arquitetura.md`  
**Última atualização:** 03/01/2026  
**Status:** UI-only (Fase 1) • **Fonte de verdade:** este arquivo + `docs/adr/`  
**Escopo:** organização de rotas, portais, módulos, sidebar e convenções (sem regra de negócio).

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
11. UI / Design System (mínimo viável)
12. Ambiente local (UI-only) — recomendado
13. Testes (baratos e que evitam typo)
14. Como adicionar um novo módulo
15. Fase 2 (contratos sem implementar)
16. ADRs (referência)

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
- **Tailwind CSS**
- **Vite**
- Dev local via **Herd** (`*.test`)

---

## 3) Estrutura do repositório
- `docs/` documentação
- `amazing/` app Laravel (código)

---

## 4) Conceitos

### 4.1 Portal (Group)
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
```
amazing/routes/
  web.php
  groups/
    amazing.php
    franchising.php
    franqueado.php
    franqueado_central.php
    loja.php
    scoped_modules.php
  modules/
    hub.php
    comercial.php
    financeiro.php
    central.php
```

### 5.2 `amazing/routes/web.php` (agregador)
- Importa os portais (rotas de entrada por painel)
- Importa os módulos escopados (onde o “trabalho” acontece)

### 5.3 `amazing/routes/groups/*.php` (portais)
- Define rotas de entrada por portal (ex.: `/loja`)
- No protótipo, o portal pode ser setado na sessão (modo debug)
- Recomendação: após setar o portal, **redirecionar para** `route('hub.index', ['scope' => 'default'])`

### 5.4 `amazing/routes/groups/scoped_modules.php`
- Define prefixo: `s/{scope}`
- Aplica middlewares:
  - `resolve_portal` (portal atual)
  - `set_scope` (scope atual)
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
  - metadados: `label`, `route`, `order`, `section`, `icon`, `permission` (futuro)

### 7.2 Enforcement (segurança)
- Middleware `module_enabled:<modulo>` em cada módulo
- Bloqueia acesso por URL direta quando o portal não permite (resposta 403)

> Regra de ouro: **a mesma lógica de allowlist/wildcard deve ser usada na sidebar e no middleware**.

---

## 8) Contexto unificado (Portal e Scope)

Para evitar divergência de regras (e bugs chatos), o sistema usa **classes de contexto** como fonte única:

- `amazing/app/Support/Context/PortalContext.php`
  - resolve portal atual (app/session)
  - valida portais disponíveis
  - aplica wildcard/allowlist de módulos
  - expõe `homeRoute()` do portal
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
  - (futuro) filtra também por permissão fina (`can(...)`)

Composer:
- `amazing/app/View/Composers/SidebarComposer.php`

Registro (View Composer):
- `amazing/app/Providers/AppServiceProvider.php`
  - `View::composer('partials.sidebar', SidebarComposer::class);`

View:
- `amazing/resources/views/partials/sidebar.blade.php`
  - pode agrupar por `section` (ex.: principal/admin)
  - recomendado mostrar `Portal` e `Scope` no header (ajuda muito em demo)

---

## 10) Controllers e Views
- Controllers: `amazing/app/Http/Controllers/<Modulo>/...`
- Views: `amazing/resources/views/<modulo>/...`
- Layout base: `amazing/resources/views/layouts/app.blade.php`

**Regra Fase 1:**
- Controller monta **dados fake** e escolhe view
- Sem regra de negócio (se começar a “pensar demais”, você saiu da Fase 1 😄)

---

## 11) UI / Design System (mínimo viável)
Estratégia:
- Tailwind para composição rápida
- Componentes Blade para reutilização

Local sugerido:
- `amazing/resources/views/components/ui/`

Componentes recomendados:
- `ui/button`, `ui/card`, `ui/badge`
- `ui/page-header` (título + ações + breadcrumbs opcional)
- `ui/table` (toolbar + paginação fake)
- `ui/empty-state` (vai aparecer muito)

Regra prática:
- Se repetiu 2x (com pequenas variações), vira componente.

---

## 12) Ambiente local (UI-only) — recomendado
Para reduzir fricção no protótipo:
- `APP_URL=http://amazing.test`
- `APP_LOCALE=pt_BR` / `APP_FALLBACK_LOCALE=pt_BR` / `APP_FAKER_LOCALE=pt_BR`
- `QUEUE_CONNECTION=sync` (sem worker)
- `CACHE_STORE=file` (sem tabela de cache)
- `AMAZING_ALLOW_PORTAL_QUERY_SWITCH=true` (apenas local)

> Observação: `QUEUE_CONNECTION=database` e `CACHE_STORE=database` exigem tabelas/migrations e tendem a gerar “erro do nada” no UI-only.

---

## 13) Testes (baratos e que evitam typo)
Teste de integridade recomendado:
- `amazing/tests/Feature/ConfigIntegrityTest.php`
  - valida que `portals.*.modules` referencia módulos existentes (exceto `'*'`)
  - valida que `modules.*.route` existe (`Route::has()`)

Comando:
```bash
cd amazing
php artisan test
```

---

## 14) Como adicionar um novo módulo

Exemplo: módulo `relatorios`.

### 14.1 Criar rota do módulo
Arquivo:
- `amazing/routes/modules/relatorios.php`

Template (padrão):
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Relatorios\RelatoriosController;

Route::middleware(['module_enabled:relatorios'])
    ->prefix('relatorios')
    ->as('relatorios.')
    ->group(function () {
        Route::get('/', [RelatoriosController::class, 'index'])->name('index');
    });
```

### 14.2 Importar o módulo em `routes/groups/scoped_modules.php`
- incluir `require` do arquivo do módulo

### 14.3 Criar controller e view (UI-only)
- Controller: `amazing/app/Http/Controllers/Relatorios/RelatoriosController.php` (opcional no protótipo)
- View: `amazing/resources/views/relatorios/index.blade.php`

> No UI-only, o controller pode retornar mock simples; quando entrar regra real, ele vira o ponto de encaixe natural.

### 14.4 Registrar no catálogo (`config/modules.php`)
Adicionar:
- `label`
- `route` (`relatorios.index`)
- `order`
- `section` (opcional)
- `icon` (opcional)
- `permission` (futuro)

### 14.5 Permitir no(s) portal(is) (`config/portals.php`)
- adicionar `relatorios` no `modules` do portal desejado (ou garantir `'*'`)

### 14.6 Checklist de validação
- `php artisan route:list | findstr relatorios`
- Acessar: `http://amazing.test/s/default/relatorios`
- Validar:
  - aparece no menu do portal correto
  - URL direta dá 403 quando módulo não está permitido

---

## 15) Fase 2 (contratos sem implementar)
- Autenticação real
- RBAC por scope (loja/franqueado/central)
- Hierarquia viva (Org Units)
- Auditoria de mudanças de acesso
- Filas (workers) e processamento assíncrono pesado
- Integrações (serviços externos, bancos, storage de anexos)

---

## 16) ADRs (referência)
Os ADRs vivem em `docs/adr/` e registram as decisões principais:
- **ADR 0001** — Módulos escopados em `/s/{scope}`
- **ADR 0002** — Portal (Group) controla macro-acesso a módulos
- **ADR 0003** — Sidebar derivada de config (sem hardcode)