# Rotas, Portais e Módulos — Padrões Práticos

## 1) Rotas atuais (padrão)
- `/loja` → define portal `loja` (fase 1: sessão)
- `/franqueado`, `/franchising`, `/amazing` … idem
- `/s/{scope}` → `hub.index`
- `/s/{scope}/comercial` → `comercial.index`
- `/s/{scope}/financeiro` → `financeiro.index`
- `/s/{scope}/central` → `central.index`

> Observação: acessar `/` pode retornar 404 (entrypoint ainda não definido). Isso é intencional por enquanto.

## 2) Convenções para módulos
Todo módulo segue:

- prefixo: `/s/{scope}/<modulo>`
- nome: `<modulo>.index`

Exemplo (financeiro):
- URL: `/s/default/financeiro`
- rota: `financeiro.index`

## 3) Onde cada coisa mora
- Portais: `routes/groups/*.php`
- Scoped modules: `routes/groups/scoped_modules.php`
- Módulos: `routes/modules/*.php`

## 4) Middleware macro de módulo
Em todo módulo (exceto hub, se desejar), usar:

- `module_enabled:<modulo>`

Exemplo:
- `module_enabled:central` impede portal `loja` de acessar Central.

## 5) Testes rápidos
- Ver rotas:
  - `php artisan route:list`

- Testar no browser:
  - `http://amazing.test/loja`
  - `http://amazing.test/s/default/comercial`

- Confirmar bloqueio macro:
  1) remover `central` do portal `loja` em `config/portals.php`
  2) acessar `/loja`
  3) acessar `/s/default/central` → deve dar 403
