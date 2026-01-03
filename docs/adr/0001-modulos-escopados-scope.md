# ADR 0001 — Módulos escopados em `/s/{scope}`

## Status
Aceito

## Contexto
O sistema terá múltiplos níveis (lojas/franqueados/central) e precisa reutilizar os mesmos módulos sem duplicar rotas por tipo de usuário. Além disso, a Fase 2 exigirá controle de acesso e permissões considerando “em qual unidade/escopo o usuário está operando”.

## Decisão
Decidimos que todos os módulos funcionais operarão sob um **prefixo escopado**:

- Hub: `/s/{scope}`
- Módulos: `/s/{scope}/<modulo>`

No protótipo UI-only, `{scope}` é apenas um identificador (ex.: `default`). Na Fase 2, `{scope}` vira o contexto real (loja/franqueado/regional).

## Consequências
✅ Benefícios
- Módulos são únicos e reutilizáveis, sem duplicar rotas por perfil.
- Portais passam a ser “entrada no contexto”, não duplicação de módulos.
- Prepara RBAC e permissões por escopo na Fase 2.

⚠️ Custos / Riscos
- É obrigatório resolver e manter o `scope` de forma consistente (middleware/contexto).
- Links e geração de rota precisam sempre incluir `scope`.
