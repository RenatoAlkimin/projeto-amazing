# Visão do Produto — Amazing (UI-only)

## O que é
**Amazing** é um protótipo **UI-only** do hub interno da Vaapty.  
O foco da fase 1 é validar:
- layout e padrões visuais
- navegação e estrutura modular
- “cara de sistema” (UX de painel)

## Por que UI-only primeiro
- muda layout e fluxo barato e rápido
- alinha expectativas com stakeholders
- prepara uma base organizada para fase funcional

## Fase 1 — Escopo (UI-only)
### Dentro
- layout base (sidebar + header + content)
- navegação entre módulos
- páginas placeholder por módulo
- mocks visuais (cards, tabelas, listas, gráficos fake)
- padrões de rotas por portal/módulo/scope
- menu governado por config (sem hardcode)

### Fora (por enquanto)
- banco e migrations
- autenticação real e RBAC real
- CRUD e regras de negócio
- integrações externas
- storage real para arquivos (S3 etc)
- processamento assíncrono (queues)

## Módulos iniciais (Fase 1)
- **Hub**: visão geral e atalhos
- **Comercial**: placeholders de pipeline/leads/metas
- **Financeiro**: placeholders de recebíveis/extratos
- **Central**: placeholders de franchising/admin

## Fase 2 — Direção (funcional)
- hierarquia (central → franqueado → loja)
- RBAC por escopo (permissões por loja/franqueado)
- performance para 100+ lojas
- filas para tarefas pesadas (extratos)
- storage S3, logs e auditoria

## Definição de pronto (DoD) — Fase 1
- rotas sem 500/404 nos módulos principais
- layout consistente entre páginas
- sidebar ativa corretamente (highlight do módulo)
- menu respeita portal → módulos
- pelo menos 1 componente de UI reutilizável (Card, Button, etc)
- docs atualizadas e passo-a-passo de execução local
