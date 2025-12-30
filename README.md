# Amazing — Hub Vaapty (protótipo UI)

**Codename interno:** Amazing  
**Empresa:** Vaapty  
**Domínio:** Intermediação de vendas de veículos  
**Status:** Protótipo **UI-only** (visual + navegação — sem banco e sem regras reais)  
**Stack:** **Laravel + Blade + Tailwind + Vite** (dev local via **Herd**)

O **Amazing** é o início de um **hub interno** para concentrar ferramentas por área (ex.: **Comercial**, **Financeiro**, **Central/Franchising**).  
Nesta fase, o foco é **estrutura visual + layout + navegação**, preparando terreno pra evoluir com segurança e escala.

---

## Links rápidos (docs)
- `docs/overview.md` — visão do projeto e escopo
- `docs/arquitetura.md` — padrões e estrutura
- `docs/roadmap.md` — fases e definição de pronto (DoD)

---

## Escopo da fase atual (UI-only)

### O que existe hoje
- Layout base (sidebar + header + content)
- Rotas e páginas placeholder por módulo
- Estrutura organizada para crescimento (módulos, views e docs)
- Mocks visuais (tabelas/listas/gráficos fake)

### O que **não** faz parte desta fase
- Autenticação, permissões e hierarquia real
- Banco de dados / CRUD real
- Integrações externas
- Processamentos pesados (extratos, uploads em fila etc.)

---

## Rodar local (Windows com Herd) — fluxo recomendado

### Pré-requisitos
- PHP compatível com Laravel
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
Ajustes recomendados no .env (Herd):

env
Copiar código
APP_URL=http://amazing.test
SESSION_DRIVER=file
Se aparecer erro do Vite/manifest, garanta que o npm run dev está rodando.
Em último caso, você pode setar: VITE_DEV_SERVER_URL=http://localhost:5173

3) Subir assets (deixe rodando)
bash
Copiar código
npm run dev
4) Linkar no Herd e acessar
bash
Copiar código
herd link
Acesse: http://amazing.test

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
php artisan view:clear
Build (produção)
bash
Copiar código
cd amazing
npm run build
Convenções (pra escalar sem bagunça)
Rotas por módulo: amazing/routes/web.php importa/organiza rotas (prefix + name por módulo)

Controllers por módulo: amazing/app/Http/Controllers/<Modulo>

Views por módulo: amazing/resources/views/<modulo>

Layout base: amazing/resources/views/layouts/app.blade.php

Estrutura do repositório
docs/ — documentação do projeto (produto + decisões)

amazing/ — aplicação Laravel (código principal)

Segurança e boas práticas
Nunca versionar .env

Não versionar vendor/, node_modules/, storage/ e builds gerados

Licença
Privado / uso interno (definir futuramente)

About do GitHub (lateral direita)
Description (curta e forte):

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