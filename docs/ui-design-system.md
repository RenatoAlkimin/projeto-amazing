# UI / Design System (mínimo viável)

## Objetivo
Garantir consistência visual e evitar duplicação de padrões.

## 1) Estratégia
- Tailwind para composição rápida
- Componentes Blade para reutilização

## 2) Onde ficam os componentes
Sugerido:
- `resources/views/components/ui/`

Exemplos:
- `ui/button.blade.php`
- `ui/card.blade.php`
- `ui/badge.blade.php`
- `ui/table.blade.php`

## 3) Convenções
- Componentes com API pequena (poucos props)
- Variantes explícitas:
  - `variant="primary|secondary|ghost|danger"`
  - `size="sm|md|lg"`
- Se repetir 2x: vira componente.

## 4) Tokens (recomendação)
No futuro, consolidar tokens no Tailwind config:
- cores semânticas (primary/surface/border/muted)
- radius padrão
- shadows padrão

## 5) Layout padrão
O layout base deve centralizar:
- sidebar
- header
- container
- título e ações
