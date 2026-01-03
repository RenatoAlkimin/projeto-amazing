# Configurações — Portais e Módulos

## 1) `config/portals.php` (Portal → Módulos)
Define **quais módulos** cada portal pode ter acesso (macro).

Exemplo:
- `amazing` pode ter `['*']`
- `loja` pode ter `['hub','comercial','financeiro']`

Regras:
- `'*'` significa acesso a todos os módulos
- Se o módulo não estiver listado, ele:
  - não aparece na sidebar
  - é bloqueado por `module_enabled:<modulo>`

## 2) `config/modules.php` (Catálogo de módulos)
Define metadados dos módulos:

Campos sugeridos:
- `label` (texto no menu)
- `route` (nome da rota principal, ex: `financeiro.index`)
- `order` (ordem no menu)
- `permission` (futuro: permissão mínima para ver o módulo)
- `section`/`icon` (opcional)

O menu deve ser sempre derivado desses configs, não hardcoded.

## 3) Como as configs se conectam
- Sidebar:
  - lê o portal atual
  - filtra módulos permitidos por `portals.php`
  - renderiza label/rota a partir de `modules.php`

- Rotas:
  - middleware `module_enabled:<modulo>` reforça a regra no backend
