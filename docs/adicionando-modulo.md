# Como adicionar um novo módulo

Exemplo: módulo `relatorios`.

## 1) Criar rota do módulo
Criar arquivo:
- `amazing/routes/modules/relatorios.php`

Padrão:
- prefix `relatorios`
- name `relatorios.`
- entry route `index`
- middleware macro `module_enabled:relatorios`

## 2) Importar o módulo
No arquivo `routes/groups/scoped_modules.php`, adicionar:
- `require_once __DIR__.'/../modules/relatorios.php';`

## 3) Criar controller e view (UI-only)
- Controller: `app/Http/Controllers/Relatorios/RelatoriosController.php`
- View: `resources/views/relatorios/index.blade.php`

Regra fase 1:
- apenas mock e render

## 4) Registrar no catálogo de módulos
Em `config/modules.php`, adicionar:
- label
- route `relatorios.index`
- order

## 5) Permitir em portais
Em `config/portals.php`, adicionar `relatorios` no portal desejado.

## 6) Testar
- `php artisan route:list`
- `http://amazing.test/s/default/relatorios`
- validar se aparece no menu do portal correto
# Como adicionar um novo módulo

Exemplo: módulo `relatorios`.

## 1) Criar rota do módulo
Criar arquivo:
- `amazing/routes/modules/relatorios.php`

Padrão:
- prefix `relatorios`
- name `relatorios.`
- entry route `index`
- middleware macro `module_enabled:relatorios`

## 2) Importar o módulo
No arquivo `routes/groups/scoped_modules.php`, adicionar:
- `require_once __DIR__.'/../modules/relatorios.php';`

## 3) Criar controller e view (UI-only)
- Controller: `app/Http/Controllers/Relatorios/RelatoriosController.php`
- View: `resources/views/relatorios/index.blade.php`

Regra fase 1:
- apenas mock e render

## 4) Registrar no catálogo de módulos
Em `config/modules.php`, adicionar:
- label
- route `relatorios.index`
- order

## 5) Permitir em portais
Em `config/portals.php`, adicionar `relatorios` no portal desejado.

## 6) Testar
- `php artisan route:list`
- `http://amazing.test/s/default/relatorios`
- validar se aparece no menu do portal correto
