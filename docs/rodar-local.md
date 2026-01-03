# Como rodar local (Herd + Vite)

## Pré-requisitos
- PHP 8.4+
- Composer
- Node.js (LTS)
- Herd

> A aplicação Laravel está em `amazing/`.

## Setup
Dentro de `amazing/`:

1) Dependências PHP:
- `composer install`

2) Env:
- copiar `.env.example` → `.env`
- `php artisan key:generate`

3) Dependências front:
- `npm install`

4) Vite (dev):
- `npm run dev`

## Acesso via Herd
- `http://amazing.test`

Se `/` der 404, use rotas diretas:
- `http://amazing.test/loja`
- `http://amazing.test/s/default`

## Limpar caches
- `php artisan optimize:clear`

## Dicas Herd
- Certifique que o site está apontando para a pasta `amazing/` (onde existe `public/` e `artisan`).
