# TESS.md — Mapa do Projeto + Checklist de Análise (Amazing / Hub Vaapty)

> **Para que serve:** este arquivo vai sempre junto no ZIP. Ele me dá **contexto, mapa da estrutura e um checklist fixo** pra eu analisar o projeto de forma consistente a cada envio.
>
> **⚠️ Segurança:** não coloque segredos aqui (tokens, senhas, chaves). Use `.env.example` com placeholders.

---

## 1) Identidade do projeto

- **Nome/Codename:** Amazing — Hub Vaapty (protótipo **UI-only**)
- **Objetivo (1 frase):** Hub interno com **layout + navegação + padrões de UI**, preparando terreno pra fase funcional.
- **Stack:** **Laravel 12 + Blade + Tailwind v4 + Vite**
- **Dev local:** recomendado via **Herd** (`*.test`), mas roda também via `php artisan serve`.

---

## 2) Onde está o que (mapa rápido)

### Raiz do ZIP
- `docs/` → documentação e decisões (ADRs)
- `amazing/` → **aplicação Laravel** (é aqui que roda)

### Dentro de `amazing/`
- `app/Http/Middleware/` → governança de **portal / scope / acesso**
- `app/Support/Context/` → contextos (`PortalContext`, `ScopeContext`)
- `app/Support/Navigation/` → sidebar por configuração (`SidebarBuilder`)
- `config/`
  - `amazing.php` → flags/valores do produto (portal/scope padrão, feature flags)
  - `portals.php` → catálogo de portais e módulos permitidos
  - `modules.php` → catálogo de módulos (label/route/icon/section/order)
  - `tenants.php` → entitlements por `scope` (Fase 1 UI-only)
- `routes/`
  - `web.php` → entradas e includes
  - `portals/*.php` → `/vaapty` e `/amazing`
  - `portals/scoped_modules.php` → prefixo `/s/{scope}` + middlewares + carga dos módulos
  - `modules/*.php` → rotas de cada módulo
- `resources/views/`
  - `layouts/app.blade.php` → layout base
  - `partials/sidebar.blade.php` / `partials/topbar.blade.php`
  - `modules/<modulo>/*` → views por módulo
- `resources/css/app.css` → Tailwind v4 + imports (theme/base/layout/components)
- `vite.config.js` → Vite + laravel-vite-plugin + @tailwindcss/vite
- `tests/Feature/*` → testes de integridade/config/acesso

---

## 3) Como rodar local (passo a passo)

> A aplicação Laravel está **dentro de `amazing/`**.

### 3.1 Requisitos
- **PHP:** ^8.2 (ver `amazing/composer.json`)
- **Composer**
- **Node + npm** (pra Vite/Tailwind)
- (Opcional) **Herd** para `amazing.test`

### 3.2 Setup rápido (mínimo viável)
```bash
cd amazing
composer install
npm install
```

### 3.3 .env (atenção importante)
- O `composer.json` tem scripts que copiam `.env.example` → `.env`…
- **Mas no ZIP atual não veio `.env.example`.**  
  Recomendo adicionar um `.env.example` no projeto pra padronizar (sem segredos).

Enquanto isso, crie um `.env` manualmente com base no `.env` padrão do Laravel e ajuste no mínimo:

**Recomendado (UI-only / baixa fricção)**
- `APP_URL=http://amazing.test`
- `APP_LOCALE=pt_BR`
- `APP_FALLBACK_LOCALE=pt_BR`
- `APP_FAKER_LOCALE=pt_BR`
- `QUEUE_CONNECTION=sync`
- `CACHE_STORE=file`
- `AMAZING_ALLOW_PORTAL_QUERY_SWITCH=true` *(só local/testing)*
- `AMAZING_ENABLE_DIAGNOSTICS=true` *(opcional, habilita módulo Diagnostics)*

**Banco (para login, stores/roles/memberships e testes)**
- Sugestão: **SQLite**
  - `DB_CONNECTION=sqlite`
  - `DB_DATABASE=/caminho/para/amazing/database/database.sqlite`  
    *(ou só deixe vazio e use o default do Laravel que aponta pra `database/database.sqlite`)*

Crie o arquivo do SQLite se não existir:
```bash
# Linux/Mac
touch database/database.sqlite
# Windows PowerShell
ni database/database.sqlite -ItemType File -Force
```

### 3.4 Key + migrations + seed
```bash
php artisan key:generate
php artisan migrate --seed
```

### 3.5 Rodar servidor + assets
**Terminal A (Laravel)**
```bash
php artisan serve
```

**Terminal B (Vite)**
```bash
npm run dev
```

Se você usa **Herd**, acesse o domínio `amazing.test` (com `APP_URL` batendo).

---

## 4) Login e Portais

### Portais (rotas de entrada)
- **Produto:** `/vaapty` (portal `vaapty`)
- **Interno/Superadmin:** `/amazing` (portal `amazing`)

### Usuário seed (dev)
No `DatabaseSeeder`, o superadmin default é:
- **username:** `admin`
- **password:** `admin`

Pode sobrescrever via env:
- `AMAZING_SEED_SUPERADMIN_USERNAME`
- `AMAZING_SEED_SUPERADMIN_PASSWORD`

> Observação: o middleware `EnsurePortalAccess` restringe o portal **amazing** a quem é **superadmin**.

---

## 5) Conceitos-chave (a “arquitetura por configuração”)

### 5.1 Portal
Define “qual painel” o usuário está usando:
- fonte: `config/portals.php`
- guardado em sessão via `PortalContext`
- o portal decide quais **módulos** aparecem e podem ser acessados

### 5.2 Scope (tenant/loja)
Contexto multi-tenant simples:
- rotas principais são escopadas: **`/s/{scope}/...`**
- entitlements por scope: `config/tenants.php`

### 5.3 Módulo
Cada módulo tem:
- config em `config/modules.php` (label/route/icon/order/section)
- rotas em `routes/modules/<modulo>.php`
- view em `resources/views/modules/<modulo>/...`
- acesso reforçado por middleware `module_enabled:<modulo>`

---

## 6) Fluxo de request (bem direto)

1) Usuário entra por `/vaapty` ou `/amazing`
2) Portal é setado em sessão (`PortalContext`)
3) A navegação e o acesso final passam por:
   - **Portal modules** (config/portals.php)
   - **Tenant modules** (config/tenants.php) — *aplicado no portal vaapty*
4) Rotas escopadas: `routes/portals/scoped_modules.php`
   - prefixo `s/{scope}`
   - middlewares principais (ordem importa):
     - `resolve_portal`
     - `set_scope`
     - `ensure_store_exists`
     - `ensure_portal_access`
     - `ensure_scope_access`
     - `module_enabled:<modulo>`

---

## 7) Sidebar (menu) — como é gerada

- **Builder:** `app/Support/Navigation/SidebarBuilder.php`
- **Composer:** `app/View/Composers/SidebarComposer.php`
- **View alvo:** `resources/views/partials/sidebar.blade.php`

Regra:
- portal **amazing**: usa módulos do portal (geralmente `*`)
- portal **vaapty**: usa **interseção** `(portal ∩ tenant(scope))`

---

## 8) Feature flags e “pegadinhas” boas

- **Diagnostics**
  - módulo existe no catálogo, mas só responde se:
    - `AMAZING_ENABLE_DIAGNOSTICS=true` **e**
    - o middleware permitir
  - se desligado, vira **404** mesmo no portal amazing

- **Troca de portal via query string**
  - `?portal=vaapty|amazing`
  - só em `local/testing` e com `AMAZING_ALLOW_PORTAL_QUERY_SWITCH=true`

- **UI-only sem dependências**
  - preferir `QUEUE_CONNECTION=sync` e `CACHE_STORE=file` pra evitar “erro do nada”.

---

## 9) Como adicionar um módulo novo (checklist)

1) **Config do módulo**  
   `amazing/config/modules.php`  
   - defina `label`, `route`, `icon`, `order`, `section`

2) **Arquivo de rotas obrigatório**  
   `amazing/routes/modules/<modulo>.php`  
   - pelo menos a rota `<modulo>.index`

3) **Views**
   - `amazing/resources/views/modules/<modulo>/index.blade.php`

4) **Aparecer no portal**
   - `amazing/config/portals.php` (ou wildcard `*`)

5) **Entitlement por scope (quando portal=vaapty)**
   - `amazing/config/tenants.php`

6) **Rodar testes**
```bash
cd amazing
php artisan test
```

> O teste `tests/Feature/ConfigIntegrityTest.php` falha cedo se:
> - portal referencia módulo inexistente
> - módulo no catálogo não tem arquivo de rotas
> - rota configurada não existe

---

## 10) Checklist da TESS quando você mandar um novo ZIP

### Primeira passada (2 min)
- [ ] Conferir se existe `amazing/` (Laravel app)
- [ ] Ler `docs/arquitetura.md` (fonte de verdade)
- [ ] Ler configs: `config/amazing.php`, `config/portals.php`, `config/modules.php`, `config/tenants.php`
- [ ] Ver rotas: `routes/web.php`, `routes/portals/scoped_modules.php`, `routes/modules/*`

### Integridade estrutural (5–10 min)
- [ ] Conferir se todo módulo em `config/modules.php` tem `routes/modules/<m>.php`
- [ ] Conferir se cada módulo tem view base `resources/views/modules/<m>/index.blade.php`
- [ ] Conferir se sidebar reflete a config (sem hardcode)

### Rodar (quando necessário)
- [ ] `composer install`
- [ ] `npm install`
- [ ] `php artisan migrate --seed`
- [ ] `php artisan test`
- [ ] `npm run build` (pra validar modo estático)

### Sinais de alerta (pra eu apontar rápido)
- [ ] novo middleware quebrando fluxo escopado
- [ ] mismatch entre config e rotas
- [ ] portal/scope permitindo acesso indevido
- [ ] `QUEUE_CONNECTION`/`CACHE_STORE` dependentes de DB sem migrations

---

## 11) Arquivos que eu (TESS) SEMPRE olho primeiro

1) `docs/arquitetura.md`
2) `amazing/config/{amazing,portals,modules,tenants}.php`
3) `amazing/routes/portals/scoped_modules.php`
4) `amazing/app/Http/Middleware/*`
5) `amazing/app/Support/Navigation/SidebarBuilder.php`
6) `amazing/resources/views/layouts/app.blade.php` + `partials/*`
7) `amazing/tests/Feature/*`

---

## 12) Anotações rápidas de versão

- **Última análise da TESS:** 2026-01-18
- **Módulos no catálogo (config/modules.php):** hub, comercial, financeiro, marketing, rh, diagnostics, controladoria

---

### (Opcional) TODO pra melhorar o “onboarding” do ZIP
- [ ] adicionar `.env.example` (sem segredos) alinhado com as recomendações acima
- [ ] colocar no README do root um “quick start” que chama `composer run dev` / `composer run setup`
