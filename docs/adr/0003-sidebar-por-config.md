# ADR 0003 — Sidebar derivada de config (sem hardcode)
## Status
Aceito

## Contexto
Menu hardcoded escala mal e gera inconsistências quando módulos e acessos mudam.

## Decisão
Sidebar renderiza itens automaticamente com base em:
- `config/portals.php`
- `config/modules.php`

## Consequências
- menu sempre consistente com as regras
- adicionar módulo vira rotina simples
- prepara o terreno para permissão fina (can)
