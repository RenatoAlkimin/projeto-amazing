# Arquitetura e decisões técnicas — Amazing (UI-only)

Este documento descreve o estado atual do protótipo, decisões de organização e convenções
para manter o projeto escalável sem virar um monólito bagunçado.

---

## 1) Princípios (Fase 1)

- **UI-only de verdade:** sem banco, sem regra de negócio, sem integrações.
- **Foco em consistência:** layout + componentes reutilizáveis.
- **Organização por módulos:** já preparar a casa pra crescer.
- **Baixa fricção no dev local:** setup simples e repetível.

---

## 2) Stack (protótipo)

- **Laravel 12** + **Blade** (SSR simples pra prototipação)
- **Tailwind CSS**
- **Vite** (assets)
- **Laravel Herd** (Windows / domínio `*.test`)

---

## 3) Estrutura do repositório

- `docs/` — documentação do produto/decisões
- `amazing/` — aplicação Laravel (código principal)

---

## 4) Organização por módulos (rotas, controllers, views)

### Rotas

**Objetivo:** manter rotas separadas por módulo, evitando `web.php` virar um carnaval.

- `amazing/routes/web.php` importa arquivos em:  
  `amazing/routes/modules/*.php`

**Padrão sugerido:**
- Cada módulo tem seu arquivo (ex.: `comercial.php`, `financeiro.php`, `central.php`)
- Rotas sempre com `prefix` e `name` do módulo, por exemplo:
  - `prefix('comercial')->name('comercial.')`

### Controllers

- `amazing/app/Http/Controllers/<Modulo>/...`

**Padrão:**
- Controller só prepara “dados fake”/mock e escolhe view.
- Nada de lógica de negócio aqui (ainda).

### Views

- `amazing/resources/views/<modulo>/...`
- Layout base: `amazing/resources/views/layouts/app.blade.php`

**Padrão:**
- Cada módulo tem:
  - `index.blade.php` (entrada)
  - `partials/` (se precisar)
- Layout base centraliza:
  - sidebar
  - header
  - container de conteúdo
  - breadcrumbs (se existir)

---

## 5) Componentes de UI (design system mínimo)

Pra UI escalar, a recomendação é usar **Blade components** desde cedo.

### Onde colocar

Opção A (mais simples no Blade):
- `amazing/resources/views/components/`

Exemplos:
- `components/card.blade.php`
- `components/button.blade.php`
- `components/badge.blade.php`
- `components/table.blade.php`
- `components/empty-state.blade.php`

### Convenções

- Componentes devem ter:
  - API pequena (poucos props)
  - estilos consistentes (Tailwind)
  - variantes claras (`variant="primary"`, `size="sm"` etc.)
- Evitar duplicar padrões em várias views.
- Se um pedaço se repete 2x, vira componente. (Sem dó.)

---

## 6) Assets: Vite + Tailwind

- Dev: `npm run dev` (mantém o Vite server rodando)
- Build: `npm run build` (pra produção)

**Dica anti-bug clássico:**  
Se der erro de manifest / Vite, confirme que o dev server está rodando e que o `.env` está coerente com o ambiente.

---

## 7) Configuração de ambiente (UI-only)

Recomendações no `.env` (Herd):

- `APP_URL=http://amazing.test`
- `SESSION_DRIVER=file` (não depender de DB)

---

## 8) Padrões de navegação (UX)

- Sidebar:
  - itens por módulo
  - destaque do item ativo
  - (futuro) colapsável
- Header:
  - título da página (h1)
  - ações de contexto (botões)
  - (futuro) usuário/empresa/região/loja

---

## 9) Preparação pra Fase 2 (sem implementar ainda)

Quando for evoluir pra fase funcional, manter as responsabilidades separadas:

- **Auth/RBAC:** Policies/Gates, middleware, roles/permissions
- **Dados pesados (extratos, anexos):** filas (queue) + storage (S3) + processamento assíncrono
- **Performance:** paginação, filtros server-side, índices e caching onde fizer sentido
- **Observabilidade:** logs estruturados + métricas básicas

> A Fase 1 só “prepara o terreno” — mas já evita decisões que travam a Fase 2.
