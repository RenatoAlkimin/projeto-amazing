# Arquitetura (inicial)

## Estrutura do repositório
- /amazing = aplicação Laravel
- /docs = documentação do produto e decisões

## Convenções (fase 1)
- Rotas: `amazing/routes/web.php` agrupadas por módulo
- Controllers: `amazing/app/Http/Controllers/<Modulo>`
- Views: `amazing/resources/views/<modulo>`
- Layout comum: `amazing/resources/views/layouts/app.blade.php`

## Objetivo
Começar simples (monólito Laravel), porém organizado por módulos para escalar sem virar bagunça.
