# ADR 0001 — Módulos escopados em `/s/{scope}`
## Status
Aceito

## Contexto
O sistema terá múltiplos níveis (lojas/franqueados) e precisa reutilizar módulos sem duplicar rotas por tipo de usuário.

## Decisão
Todos os módulos funcionais rodam sob:
- `/s/{scope}/<modulo>`
e o hub é:
- `/s/{scope}`

## Consequências
- módulos são únicos e reutilizáveis
- portais apenas “entram no contexto”
- facilita RBAC por escopo na fase 2
