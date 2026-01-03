# Troubleshooting

## 1) `RouteNotFoundException` (ex.: `*.home`)
Causa:
- views ainda apontando para rotas antigas (`comercial.home` etc)

Solução:
- padronizar para `*.index`
- garantir `scope`:
  - `route('comercial.index', ['scope' => $scope])`

Buscar ocorrências:
- PowerShell:
  - `Select-String -Path "resources\views\**\*.blade.php" -Pattern "route\('.*\.home'"`

## 2) 403 ao acessar módulo
Causa:
- portal atual não permite o módulo (config/portals.php + module_enabled)

Solução:
- verificar portal atual (sessão)
- verificar se módulo está na lista do portal
- validar middleware `module_enabled:<modulo>` no routes/modules

## 3) `/` 404
Causa:
- entrypoint ainda não definido

Solução:
- use `/loja` e `/s/default` diretamente
- (futuro) criar rota `/` que redireciona

## 4) Vite/manifest
- rodar `npm run dev`
- ou `npm run build` e garantir assets
- limpar cache: `php artisan optimize:clear`

## 5) Herd “refused to connect”
- verificar se Herd está rodando
- confirmar site apontando para `amazing/`
