# Roadmap

## Fase 0 — Setup e trilhos (base do projeto)
**Objetivo:** garantir que o projeto roda e é fácil de evoluir.
- Herd configurado e app acessível em `amazing.test`
- Repo organizado (`/docs` + `/amazing`)
- `.gitignore` blindado (sem `.env`, `vendor`, `node_modules`, `storage`)
- README com “como rodar do zero”

**Definição de pronto (DoD)**
- Qualquer dev clona e roda seguindo README sem dor.

---

## Fase 1 — Protótipo UI (Hub + módulos)
**Objetivo:** UI navegável com layout consistente.
- Layout base (sidebar + header)
- Rotas do hub + módulos
- Páginas placeholder (Hub / Comercial / Financeiro / Central)
- Componentes iniciais:
  - Card
  - Botão
  - Badge/Status
  - Tabela (mock)
  - Empty state

**DoD**
- Navegação fluida entre módulos
- Layout consistente e reaproveitável

---

## Fase 1.5 — UI “enterprise” (refino)
**Objetivo:** deixar com cara de sistema grande.
- Sidebar colapsável
- Header com busca + usuário (mock)
- Breadcrumbs
- Padrão de tabelas: paginação mock + filtros mock
- Padrão de formulários (inputs, selects, validação visual)

**DoD**
- Design system mínimo padronizado e replicável

---

## Fase 2 — Funcional (quando entrar backend de verdade)
**Objetivo:** sistema começa a “funcionar” com regras reais.
- Autenticação
- Perfis e permissões (RBAC)
- Hierarquia (loja → gerente → regional → central)
- CRUDs reais por módulo (começar pelo Comercial)
- Auditoria básica (log de ações)

**DoD**
- Usuários logam e veem apenas o que podem
- CRUDs principais funcionando com padrões de UI/UX

---

## Fase 3 — Escala e robustez
**Objetivo:** suportar rede grande com performance e observabilidade.
- Multi-loja (tenant)
- Filas (queue) para processamento pesado (extratos/importações)
- Cache (Redis) e paginação real
- Storage de imagens/anexos (S3/compatível)
- Notificações (web e base para mobile)
- Observabilidade (logs/métricas) + rotinas de backup

**DoD**
- Fluxos pesados assíncronos
- Performance consistente em listas grandes
- Base pronta para crescimento (100+ lojas)
