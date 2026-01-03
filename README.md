# Amazing — Hub Vaapty (Protótipo UI)

**Codename interno:** Amazing  
**Empresa:** Vaapty  
**Domínio:** Intermediação de vendas de veículos  
**Status:** Protótipo **UI-only** (visual + navegação — sem banco e sem regras reais)  
**Stack:** **Laravel 12 + Blade + Tailwind + Vite** (dev local via **Herd**)

O **Amazing** é o início de um **hub interno** para concentrar ferramentas por área (ex.: **Comercial**, **Financeiro**, **Central/Franchising**).  
Nesta fase, o foco é **estrutura visual + layout + navegação**, preparando o terreno pra evoluir com segurança e escala.

---

## Links rápidos (docs)

- `docs/README.md` — índice da documentação
- `docs/visao-produto.md` — visão do projeto e escopo
- `docs/arquitetura.md` — decisões técnicas e organização
- `docs/rotas-portais-modulos.md` — padrões de rotas, portais e módulos
- `docs/rodar-local.md` — setup local (Herd + Vite)
- `docs/adicionando-modulo.md` — receita pra criar módulo novo
- `docs/troubleshooting.md` — erros comuns e soluções
- `docs/adr/` — decisões registradas (ADR)

---

## Escopo da fase atual (UI-only)

### O que existe hoje
- Layout base (sidebar + header + content)
- Rotas e páginas placeholder por módulo
- Navegação por **módulos** e **portais**
- Estrutura organizada para crescimento (**groups**, **modules**, configs, docs)
- Mocks visuais (tabelas/listas/gráficos fake)

### O que **não** faz parte desta fase
- Autenticação real, permissões finas e hierarquia real (Fase 2)
- Banco de dados / CRUD real
- Integrações externas
- Processamentos pesados (extratos, uploads em fila etc.)

---

## Conceitos do projeto (rápido)

### Portais (Groups)
Portais representam o “tipo de painel” (grupo de acesso) e controlam **macro-acesso** a módulos:
- `amazing`, `franchising`, `franqueado`, `franqueado_central`, `loja`

### Módulos (Modules)
Módulos são as áreas funcionais:
- `hub`, `comercial`, `financeiro`, `central`, etc.

### Scope (Contexto)
Módulos rodam em rotas escopadas:
- `/s/{scope}` → Hub
- `/s/{scope}/comercial`
- `/s/{scope}/financeiro`
- `/s/{scope}/central`

> No protótipo, `{scope}` é um identificador (ex.: `default`). Na Fase 2 vira contexto real (loja/franqueado/regional).

---

## Macro-acesso por portal (Portal → Modules)

A “governança” do que aparece e do que pode ser acessado é definida por config:

- `amazing/config/portals.php` → portal → módulos permitidos  
- `amazing/config/modules.php` → catálogo de módulos (label, rota, ordem, etc.)

Além do menu, as rotas são reforçadas por middleware:
- `module_enabled:<modulo>` (bloqueia acesso por URL direta quando o portal não permite)

---

## Rodar local (Windows + Herd) — fluxo recomendado

> A aplicação Laravel está dentro da pasta `amazing/`.

### Pré-requisitos
- PHP compatível com Laravel 12
- Composer
- Node.js + npm
- Laravel Herd (Windows)

### 1) Instalar dependências
```bash
cd amazing
composer install
npm install
2) Configurar .env
bash
Copiar código
copy .env.example .env
php artisan key:generate
Recomendado no .env (Herd):

env
Copiar código
APP_URL=http://amazing.test
SESSION_DRIVER=file
3) Subir assets (deixe rodando)
bash
Copiar código
npm run dev
4) Acessar no Herd
Garanta que o Herd está apontando para a pasta amazing/ (onde existe public/ e artisan).
Acesse:

http://amazing.test/loja (seta portal loja)

http://amazing.test/s/default (hub no scope default)

Observação: o / pode retornar 404 por enquanto (entrypoint ainda não definido).

Comandos úteis
Listar rotas
bash
Copiar código
cd amazing
php artisan route:list
Limpar caches
bash
Copiar código
cd amazing
php artisan optimize:clear
Build (produção)
bash
Copiar código
cd amazing
npm run build
Convenções (pra escalar sem bagunça)
Rotas
amazing/routes/web.php importa:

amazing/routes/groups/*.php (portais)

amazing/routes/groups/scoped_modules.php (módulos em /s/{scope})

Módulos:

amazing/routes/modules/<modulo>.php

rota de entrada do módulo: <modulo>.index

URL: /s/{scope}/<modulo>

Controllers
amazing/app/Http/Controllers/<Modulo>/...

UI-only: apenas dados fake/mock + render de view

Views
amazing/resources/views/<modulo>/...

Layout base: amazing/resources/views/layouts/app.blade.php

Menu (Sidebar)
Deve ser derivado de:

config/portals.php + config/modules.php

Sem hardcode de links “na mão” (pra não divergir do controle macro)

Segurança e boas práticas
Nunca versionar .env

Não versionar vendor/, node_modules/, storage/ e builds gerados

Macro-acesso sempre reforçado com middleware module_enabled:<modulo>

Licença
Privado / uso interno (definir futuramente)

About do GitHub (lateral direita)
Description:
Hub interno Vaapty (UI-only). Laravel + Blade + Tailwind + Vite.

Topics (recomendados):

laravel

php

blade

tailwindcss

vite

dashboard

admin

prototype

internal-tools