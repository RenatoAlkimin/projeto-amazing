```md
# Amazing — Hub Vaapty (pré-projeto)

**Codename interno:** Amazing  
**Cliente:** Vaapty (intermediação de vendas de veículos)  
**Status:** UI-only — protótipo visual (sem banco e sem regras reais)

Este repositório inicia o **Hub Vaapty**, uma central interna para módulos como **Comercial**, **Financeiro** e **Central/Franchising**.  
Nesta fase, o foco é **estrutura + layout + navegação**, preparando terreno para evoluir com segurança e escala.

---

## Escopo da fase atual (UI-only)

### O que existe hoje
- Layout base (sidebar + header)
- Rotas e páginas placeholder por módulo
- Estrutura organizada para crescimento (módulos, views e docs)

### O que NÃO faz parte desta fase
- Autenticação, permissões e hierarquia real
- Banco de dados / CRUD real
- Integrações externas
- Processamentos pesados (extratos, uploads em fila etc.)

---

## Stack
- **Backend/Views:** Laravel (PHP) + Blade
- **UI:** Tailwind CSS
- **Assets:** Vite (Node.js)
- **Dev local (Windows):** Laravel Herd

---

## Estrutura do repositório

```

docs/                  # documentação do projeto (produto + decisões)
overview.md
arquitetura.md
roadmap.md

amazing/               # aplicação Laravel (código principal)
app/
routes/
resources/
...

````

---

## Rodar local (Windows com Herd) — fluxo recomendado

### 1) Instalar dependências
```bash
cd amazing
composer install
npm install
````

### 2) Configurar `.env`

```bash
copy .env.example .env
php artisan key:generate
```

Ajuste recomendado no `.env` (Herd):

```env
APP_URL=http://amazing.test
SESSION_DRIVER=file
```

> Se aparecer erro do Vite/manifest, garanta que o `npm run dev` está rodando.
> Em último caso, você pode setar:
> `VITE_DEV_SERVER_URL=http://localhost:5173`

### 3) Subir assets (deixe rodando)

```bash
npm run dev
```

### 4) Linkar no Herd e acessar

```bash
herd link
```

Acesse:

* [http://amazing.test](http://amazing.test)

---

## Comandos úteis

### Listar rotas

```bash
cd amazing
php artisan route:list
```

### Limpar caches

```bash
cd amazing
php artisan optimize:clear
php artisan view:clear
```

### Build (produção)

```bash
cd amazing
npm run build
```

---

## Convenções (para escalar sem bagunça)

* Rotas: `amazing/routes/web.php` agrupadas por módulo (`prefix` + `name`)
* Controllers: `amazing/app/Http/Controllers/<Modulo>`
* Views: `amazing/resources/views/<modulo>`
* Layout base: `amazing/resources/views/layouts/app.blade.php`

---

## Documentação

* `docs/overview.md` — visão do projeto e escopo
* `docs/arquitetura.md` — padrões e estrutura
* `docs/roadmap.md` — fases e definições de pronto

---

## Segurança e boas práticas

* **Nunca versionar `.env`**
* Não versionar `vendor/`, `node_modules/`, `storage/` e builds

---

## Licença

Privado / uso interno (definir futuramente).

```

---

## About do GitHub (lateral direita)

**Description (curta e forte):**  
> Hub interno Vaapty (UI-only). Laravel + Blade + Tailwind + Vite.

**Topics (recomendados):**
- `laravel`
- `php`
- `blade`
- `tailwindcss`
- `vite`
- `dashboard`
- `admin`
- `prototype`
- `internal-tools`

---


::contentReference[oaicite:0]{index=0}
```
