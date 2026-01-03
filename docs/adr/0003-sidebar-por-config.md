# ADR 0003 — Sidebar derivada de config (sem hardcode)

## Status
Aceito

## Contexto
Menu hardcoded escala mal e cria inconsistências quando módulos e acessos mudam (ex.: link aparece no menu mas dá 403, ou rota existe mas ninguém vê). Precisamos que a navegação seja sempre coerente com a governança de acesso.

## Decisão
Decidimos que a sidebar será gerada automaticamente com base em configuração:

- `config/portals.php` define quais módulos o portal pode acessar.
- `config/modules.php` define catálogo e metadados (label/route/order/section/icon/permission).
- URLs são geradas via `route(<routeName>, ['scope' => <scope>])`.
- Item ativo é identificado com `request()->routeIs('<modulo>.*')`.

## Consequências
✅ Benefícios
- Menu sempre consistente com regras de macro-acesso.
- Adicionar módulo vira rotina previsível (config + rota + view/controller).
- Prepara o terreno para permissões finas (ex.: `can(...)`) sem refazer navegação.

⚠️ Custos / Riscos
- Se `config/modules.php` referenciar rotas inexistentes, itens podem sumir sem aviso.
  - Recomendação: ter teste de integridade para rotas/config.
- A sidebar e o middleware precisam usar a mesma regra de macro-acesso para evitar inconsistências.
