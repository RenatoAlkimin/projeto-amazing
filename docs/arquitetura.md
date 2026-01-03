# Arquitetura e Decisões Técnicas — Amazing (UI-only)

Este documento descreve o estado atual do protótipo, decisões de organização e convenções
para manter o projeto escalável e governável.

## 1) Princípios (Fase 1)
- **UI-only de verdade:** sem DB, sem regra de negócio, sem integrações.
- **Estrutura pra crescer:** modularidade desde o começo.
- **Consistência acima de liberdade:** padrões visuais e rotas previsíveis.
- **Portal ≠ Módulo ≠ Permissão fina:** separação clara de responsabilidades.

## 2) Stack
- Laravel 12 + Blade
- Tailwind CSS
- Vite
- Herd (`*.test`)

## 3) Estrutura do repositório
- `docs/` documentação
- `amazing/` app Laravel

## 4) Conceitos
### 4.1 Portal (Group)
Portal representa “tipo de acesso/painel”:
- `amazing`, `franchising`, `franqueado`, `franqueado_central`, `loja`

Portal define:
- home do painel
- **macro-acesso** a módulos (ex.: loja não enxerga central)

### 4.2 Módulo (Module)
Módulo é um domínio funcional:
- hub, comercial, financeiro, central…

Módulo define:
- rotas do módulo (urls e nomes)
- controllers/views do módulo
- (futuro) permissões finas do que pode fazer dentro do módulo

### 4.3 Scope
As rotas dos módulos são escopadas por `/{scope}`:
- `/s/{scope}` (hub)
- `/s/{scope}/comercial`
- `/s/{scope}/financeiro`

No UI-only, scope é um identificador (ex.: `default`).
Na fase 2 vira contexto real (loja, franqueado etc).

## 5) Organização das rotas

### 5.1 Pastas e arquivos

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


### 5.2 `routes/web.php`
- Serve como agregador.
- Importa os grupos (ordem decrescente de acesso).
- Importa o grupo de módulos escopados.

### 5.3 `routes/groups/*.php` (portais)
- Define rotas de entrada por portal (ex.: `/loja`).
- No protótipo, pode setar o portal na sessão pra teste.

### 5.4 `routes/groups/scoped_modules.php`
- Define o prefixo: `s/{scope}`
- Aplica middlewares:
  - `resolve_portal` (portal atual)
  - `set_scope` (scope atual)
- Importa os módulos.

### 5.5 `routes/modules/*.php` (módulos)
- Cada módulo é um arquivo.
- Convenção:
  - `prefix('<modulo>')`
  - `as('<modulo>.')`
  - rota de entrada é sempre `->name('index')`

## 6) Convenções de nomes
- Entrada do módulo: `*.index`
- Exemplo:
  - `comercial.index`, `financeiro.index`, `central.index`, `hub.index`

## 7) Macro-acesso (Portal → Módulos)
### Fonte de verdade
- `config/portals.php` define quais módulos o portal pode acessar.
- `config/modules.php` define metadados dos módulos (label/rota/ordem/permission).

### Enforcement (segurança)
- middleware `module_enabled:<modulo>` em cada módulo
- impede acesso por URL direta quando o portal não permite

## 8) Sidebar governada por config
A sidebar é renderizada a partir de:
- `config/portals.php` (módulos permitidos)
- `config/modules.php` (catálogo dos módulos)

Regras:
- só renderiza itens que o portal permite
- gera URL via `route(<rota>, ['scope' => <scope>])`
- marca item ativo com `request()->routeIs('<modulo>.*')`
- (futuro) filtra também por permissão fina `can(...)`

## 9) Controllers e Views
- Controllers: `app/Http/Controllers/<Modulo>/...`
- Views: `resources/views/<modulo>/...`
- Layout base: `resources/views/layouts/app.blade.php`

Regra fase 1:
- controller monta dados fake e escolhe view
- sem regra de negócio

## 10) Fase 2 (contratos sem implementar)
- RBAC por escopo
- hierarquia viva (central/franqueado/loja)
- auditoria de mudanças de acesso
- filas e processamento assíncrono