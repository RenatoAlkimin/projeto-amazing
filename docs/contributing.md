# Contribuindo — Amazing (Hub Vaapty)

Valeu por contribuir ❤️  
Este projeto está na **Fase 1 (UI-only)**: foco em **layout, navegação e padrões de UI**.  
Sem DB, sem integrações e sem regra de negócio real (por enquanto).

---

## 1) Princípios da Fase 1

- **UI-only de verdade:** nada de migrations/schema, nada de CRUD real.
- **Consistência > criatividade:** padrões repetíveis ganham.
- **Se repetiu 2x, vira componente.**
- **Código limpo e modular:** preparar terreno pra fase funcional.

---

## 2) Setup local

Siga o passo a passo do `README.md`.

Checklist rápido:
- `composer install`
- `npm install`
- `.env` + `php artisan key:generate`
- `npm run dev` (rodando)
- Herd: `herd link` → `http://amazing.test`

---

## 3) Convenções de estrutura

### Rotas por módulo
- Evite entupir `routes/web.php`.
- Preferir rotas separadas por módulo (ex.: `routes/modules/*.php`), com:
  - `prefix('<modulo>')`
  - `name('<modulo>.')`

### Controllers por módulo
- `app/Http/Controllers/<Modulo>/...`
- Controller na Fase 1 deve:
  - retornar view
  - preparar dados **mock/fake** (arrays, fixtures simples)
- **Sem** regras de negócio e **sem** dependência de DB.

### Views por módulo
- `resources/views/<modulo>/...`
- Layout base fica em:
  - `resources/views/layouts/app.blade.php`

### Componentes (design system)
- Prefira Blade components em:
  - `resources/views/components/`
- Componentes sugeridos:
  - `Card`, `Button`, `Badge`, `Table`, `EmptyState`, `Tabs`
- Componentes devem ter:
  - API pequena (poucos props)
  - variantes claras (ex.: `variant`, `size`)
  - consistência visual (Tailwind)

---

## 4) Padrões de UI/UX

- Estilo: **corporativo simples**, limpo, “enterprise”.
- **Desktop-first**, mas não pode quebrar no mobile.
- Tabelas e listas:
  - cabeçalho fixo/clareza de colunas
  - estados de vazio (empty state)
  - paginação/filtros **UI** (mesmo fake)

Estados obrigatórios por tela (quando fizer sentido):
- **Loading** (fake ok)
- **Empty**
- **Error** (mensagem amigável)

---

## 5) Regras para mocks (dados fake)

- Mocks devem ser **simples e legíveis** (arrays no controller ou um arquivo helper/fixture).
- Evite “lorem” demais: use dados realistas do domínio (veículos, leads, lojas).
- Não introduzir bibliotecas pesadas só pra mock.

---

## 6) O que NÃO vamos aceitar (na Fase 1)

- Dependência de banco de dados (migrations, models com persistência real)
- Auth real / RBAC real
- Integrações com serviços externos
- Jobs/filas/processamento pesado
- Código duplicado em várias views quando dá pra virar componente

---

## 7) Checklist antes de abrir PR

### Funcional
- [ ] Rotas abrem sem erro (0 tela 500)
- [ ] Navegação (sidebar) funciona e destaca item ativo
- [ ] Layout consistente (header/sidebar/content)

### UI
- [ ] Componentes reutilizáveis usados (sem duplicação desnecessária)
- [ ] Estados de empty/loading quando aplicável
- [ ] Responsivo básico ok (mobile não explode)

### Qualidade
- [ ] `php artisan route:list` ok
- [ ] `php artisan optimize:clear` rodou sem erro (se necessário)
- [ ] `npm run dev` / `npm run build` sem quebrar assets (quando aplicável)

### Docs
- [ ] Se mexeu em padrões/rotas/layout, atualizou `docs/arquitetura.md` ou `docs/overview.md`

---

## 8) Padrão de commit e PR

### Commits
- Preferir mensagens no formato:
  - `ui: adiciona componente Card`
  - `ui: cria layout base (sidebar/header)`
  - `routes: organiza rotas do módulo comercial`

### Pull Request
Inclua:
- O que foi feito (bullets)
- Prints (se alterou UI)
- Como testar localmente

---

## 9) Precisa discutir algo antes?
Se a mudança altera padrões globais (layout base, sidebar, tokens de UI, estrutura de rotas),
abra a PR pequena e descreva o trade-off — ou combine antes com o responsável do projeto.

Bora manter isso aqui bonito e escalável 😄
