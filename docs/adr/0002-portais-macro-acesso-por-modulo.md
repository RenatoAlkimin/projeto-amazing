# ADR 0002 — Portal (Group) controla macro-acesso a módulos
## Status
Aceito

## Contexto
O sistema terá diferentes painéis (amazing/franchising/franqueado/loja). Precisamos controlar rapidamente quais módulos cada painel pode enxergar.

## Decisão
Criar `config/portals.php` definindo portal → módulos permitidos. Reforçar com middleware:
- `module_enabled:<modulo>`

## Consequências
- fácil governança (um lugar muda tudo)
- segurança: URL direta também é bloqueada
- não substitui permissão fina (fase 2)
