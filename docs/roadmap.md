# Roadmap — Amazing (Hub Vaapty)

Este roadmap descreve as fases do projeto e critérios de “pronto” (DoD) para cada etapa.

---

## Fase 1 — UI-only (atual)

### Objetivo
Validar estrutura visual, navegação e padrões de UI com um protótipo navegável.

### Backlog (prioridade)
1. Consolidar layout base (sidebar/header/content) e navegação
2. Criar design system mínimo (Card, Button, Badge, Table, Empty state)
3. Implementar placeholders com dados mockados (tabelas e gráficos fake)
4. Revisar com stakeholders e ajustar UI/fluxos
5. Só então iniciar fase funcional (auth, permissões, DB, integrações)

---

## Definição de pronto (DoD) — Fase 1

- Hub e módulos abrem sem erro (**0 telas 500**)
- Navegação básica funcionando (sidebar)
- Layout consistente (header/sidebar/content)
- Pelo menos **1 componente reutilizável** criado (ex.: Card)
- Docs atualizados (`overview`, `arquitetura`, `roadmap`) e README ensinando rodar do zero

---

## Fase 2 — Base funcional (planejada)

### Objetivo
Criar fundação real do sistema sem quebrar padrões da UI.

**Escopo sugerido:**
- Autenticação
- RBAC/permissões (roles + permissions)
- Estrutura inicial de banco (mínima)
- Padrões de listagem (tabela com paginação/filtros reais)
- Logs básicos + tratamento de erros

---

## Fase 3 — Primeiros módulos reais (planejada)

### Objetivo
Tornar 1–2 módulos “de verdade” usáveis (mesmo que simples).

**Exemplos:**
- Comercial: pipeline/leads básico
- Financeiro: telas de recebíveis (com dados reais e filtros)

---

## Perguntas em aberto (próxima rodada)

- Identidade visual Vaapty: cores/logotipo/fontes já existem?
- Quais módulos entram no Hub logo de cara (prioridade 1)?
- Sidebar fixa ou colapsável?
- Protótipo wireframe (rápido) ou high-fidelity (quase final)?
- Quais indicadores/gráficos são indispensáveis no Hub (mesmo que fake)?

---

## Critérios de sucesso (Fase 1)

- Stakeholders conseguem “navegar e entender” o sistema sem explicação longa
- Padrões visuais estão consistentes
- Estrutura do código já está preparada para crescer por módulos
- Não existem “atalhos sujos” que vão cobrar juros na Fase 2
