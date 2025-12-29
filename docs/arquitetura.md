# Arquitetura (inicial)

## Objetivo
Começar simples (monólito Laravel), mas **organizado por módulos** para crescer sem virar um “Frankenstein”.
Nesta fase, a arquitetura foca em **UI + organização** (sem DB e sem regras reais).

---

## Estrutura do repositório
- `/amazing` — aplicação Laravel (código principal)
- `/docs` — documentação do produto, decisões e roadmap



---

## Organização por módulos (convenção)
**Rotas**
- `amazing/routes/web.php` com rotas agrupadas por módulo (`prefix` + `name`).
- Exemplo de padrões:
  - `Route::get('/', ...)->name('hub.home')`
  - `Route::prefix('comercial')->name('comercial.')->group(...)`

**Controllers**
- `amazing/app/Http/Controllers/<Modulo>/...`
- Controllers **finos**: recebem request e retornam view.
- Regra de negócio pesada (no futuro) vai para:
  - `app/Services` / `app/Actions` / `app/Domain` (quando entrar funcional)

**Views**
- `amazing/resources/views/<modulo>/...`
- Layout base em:
  - `amazing/resources/views/layouts/app.blade.php`

**Components (design system)**
- Componentes Blade em:
  - `amazing/resources/views/components/...`
- Ideia: botões, badges, cards, tabela, empty states e etc.

---

## Fluxo HTTP (como funciona)
1. Request chega em `public/index.php`
2. `routes/web.php` define qual controller/método atende
3. Controller retorna `view(...)`
4. Blade renderiza usando layout + componentes
5. Assets (Tailwind/Vite) entram via `@vite(...)`

---

## Frontend (Tailwind + Vite)
- Tailwind/Vite rodam via Node apenas para **gerar CSS/JS**.
- Em desenvolvimento:
  - `npm run dev` (hot reload)
- Em build/produção:
  - `npm run build` → gera `public/build`

---

## Ambiente (fase UI)
Recomendado no `.env` (dev com Herd):
- `APP_URL=http://amazing.test`
- `SESSION_DRIVER=file` (evita dependência de DB)

---

## Padrões mínimos (para escalar bem)
- **Nomes previsíveis**: rotas e views com estrutura estável
- **Separação por módulo**: evitar “tudo no mesmo lugar”
- **Nada de secrets no Git**: `.env` nunca versiona
- **Evitar pastas pesadas no Git**: `vendor/`, `node_modules/`, `storage/` etc.

---

## Direção de evolução (quando entrar funcional)
Quando sair do UI-only, os pilares serão:
- **Auth + RBAC** (papéis/permissões) e hierarquia
- **Multi-loja (tenant)** (por loja / franquia)
- **Filas (queue)** para processamento pesado (extratos/importações)
- **Storage** para imagens/anexos (ex: S3/compatível)
- **Cache/Redis** para performance (sessão, cache de consultas, rate-limit)
- **Observabilidade**: logs estruturados e métricas
