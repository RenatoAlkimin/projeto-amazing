# ADR 0006 — Rotas modulares por scope e integridade de configuração (portais/módulos)

## Status
Aceito

## Contexto
O projeto possui múltiplos **portais** (ex.: amazing, franchising, franqueado, etc.) e diversos **módulos** que podem estar habilitados ou não dependendo do portal.

Com o crescimento do número de módulos e variações por portal, surgiram necessidades claras:
- Padronizar como as rotas “onde o trabalho acontece” são acessadas por **scope** (ex.: tenant/unidade/ambiente).
- Evitar inconsistências entre `config/portals.php` e `config/modules.php` (portal referenciando módulo inexistente, módulo sem rotas registradas, etc.).
- Garantir que o CI/testes não quebrem por dependências de build front-end (ex.: **Vite manifest** ausente no ambiente de testes).

## Decisão
1) **Padronizar rotas “escopadas”** sob um prefixo único:
- Prefixo: `s/{scope}`
- Grupo de middleware: `web`, `resolve_portal`, `set_scope` (e, futuramente, `auth`)

Exemplo de paths:
- `GET /s/{scope}/hub` (Hub do portal)
- `GET /s/{scope}/{module}` (entrada do módulo)

2) **Centralizar a ativação de módulos via middleware**
- Middleware `EnsureModuleEnabled` bloqueia acesso a módulos não habilitados para o portal atual.
- O portal atual é resolvido via middleware `ResolvePortal`.
- O `scope` é fixado via middleware `SetScope`.

3) **Aliases de middleware no bootstrap (Laravel 11)**
- Adicionar aliases em `bootstrap/app.php`:
  - `resolve_portal` => `App\Http\Middleware\ResolvePortal`
  - `module_enabled` => `App\Http\Middleware\EnsureModuleEnabled`
  - `set_scope` => `App\Http\Middleware\SetScope`

4) **Organização de rotas por grupos e módulos**
- `routes/web.php` inclui:
  - `routes/groups/*.php` (portais)
  - `routes/groups/scoped_modules.php` (módulos escopados)
- `routes/groups/scoped_modules.php` registra/agrupa os módulos escopados usando `config('modules')`.

5) **Testes de integridade de configuração**
Criar `Tests\Feature\ConfigIntegrityTest` para garantir:
- Portais referenciam apenas módulos existentes.
- Cada módulo configurado tem `route` e esta rota está registrada.
- Cada módulo configurado possui arquivo de rotas esperado.

6) **Tornar views resilientes em ambiente de teste (Vite)**
Como o manifest do Vite (`public/build/manifest.json`) não existe em testes, views que dependem dele devem:
- Evitar falhar em ambiente `testing`, usando fallback (ex.: `asset()`) ou renderização condicional.
Exemplo aplicado:
- `partials/topbar.blade.php` passou a usar fallback quando `Vite::asset(...)` não está disponível.

## Consequências
✅ Benefícios
- Estrutura de rotas previsível e consistente: tudo “escopado” fica em `s/{scope}`.
- Módulos ficam plugáveis, com ativação por portal controlada centralmente.
- Redução de regressões: inconsistências de config/rotas passam a ser detectadas por testes.
- Testes/CI mais estáveis: views não quebram por ausência de build front-end.

⚠️ Custos / Riscos / Trade-offs
- Registro dinâmico/centralizado de módulos exige disciplina na configuração (`config/modules.php`) e arquivos de rotas.
- Necessidade de manter convenções claras (nome de módulo, nome de rota, arquivo de rotas).
- Um fallback mal aplicado pode mascarar problemas reais do Vite em ambientes que deveriam ter build; por isso o fallback deve ser restrito a `testing`/ausência de manifest.

## Referências
- `bootstrap/app.php` (aliases de middleware)
- `routes/web.php` e `routes/groups/scoped_modules.php`
- `app/Http/Middleware/ResolvePortal.php`
- `app/Http/Middleware/SetScope.php`
- `app/Http/Middleware/EnsureModuleEnabled.php`
- `tests/Feature/ConfigIntegrityTest.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/partials/topbar.blade.php`
