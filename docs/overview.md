# Amazing (pré-projeto) — Hub Vaapty (protótipo UI)

**Codename interno:** Amazing  
**Empresa:** Vaapty  
**Domínio:** Intermediação de vendas de veículos  
**Status:** Protótipo **UI-only** (visual + navegação)  
**Stack do protótipo:** **Laravel + Blade + Tailwind + Vite** (dev local via **Herd**)  

---

## 1) Contexto e propósito

O **Amazing** é o início de um **hub interno** para a Vaapty, pensado para concentrar ferramentas por área (ex.: **Comercial**, **Financeiro**, **Central/Franchising**).

Nesta fase, o objetivo é validar:

- **Estrutura visual**
- **Navegação**
- **Padrões de UI**
- “Cara de sistema” (densidade de informação, consistência e ergonomia)

Antes de investir pesado em regras de negócio, integrações e escala.

### Por que começar com protótipo UI-only

- Reduz custo de mudança (mexer em layout/fluxo é mais barato agora)
- Alinha expectativas com stakeholders (“é isso que vocês querem ver”)
- Cria um **trilho de organização** para quando o sistema ficar grande

---

## 2) Visão do produto (futuro)

Construir um hub interno com:

- **Módulos por área** (Comercial, Financeiro, Central/Franchising, etc.)
- **Painéis e relatórios** com grande volume de informação (tabelas, gráficos, listas)
- **Fluxos operacionais** para loja física e visão macro para central/franchising

---

## 3) Requisitos futuros (contexto — não implementados agora)

- **Escala:** 100+ lojas
- **Hierarquia:** múltiplos níveis (loja → regional → central/franchising)
- **Usuários:** muitas contas + controle de acesso robusto (RBAC/Permissões)
- **Notificações:** web (PC) e base para mobile
- **Performance:** alto volume de dados, navegação fluida
- **Imagens/anexos:** armazenamento e entrega eficientes
- **Extratos bancários pesados:** processamento e relatórios com alto volume (assíncrono)

---

## 4) Escopo da fase atual (Fase 1 — UI-only)

### Dentro do escopo

- **Somente UI** (protótipo visual)
- **Navegação** entre módulos
- **Layout base** (sidebar + header + área de conteúdo)
- **Páginas placeholder** por módulo
- **Mocks visuais** (tabelas/gráficos/listas fake)

### Fora do escopo (por enquanto)

- Banco de dados / migrations / schema
- Autenticação real / permissões
- CRUD real / regras de negócio
- Integrações (banco, serviços externos)
- Armazenamento real de arquivos/imagens

---

## 5) Objetivos (Fase 1)

- Definir uma **estrutura de navegação clara** (menu, header, layout base)
- Criar páginas “vazias” (placeholders) representando os setores
- Estabelecer um **design system mínimo** (tipografia, espaçamentos, componentes base)
- Entregar um protótipo navegável para discutir “como vai ficar” antes da fase funcional

---

## 6) Entregáveis (Fase 1)

### Must-have

- Layout base: **sidebar + header + content**
- Hub (Home/Dashboard): cards/atalhos para módulos
- Páginas placeholder:
  - Comercial
  - Financeiro
  - Central (Franchising)

### Nice-to-have (se der tempo)

- “Últimas atividades” fake no Hub
- Breadcrumbs
- Tabela fake com paginação/filtros (UI)
- Form fake (UI)
- Componentes base (Card, Button, Badge, Tabs)

---

## 7) Arquitetura de informação (rascunho de rotas)

### Rotas principais (protótipo)

- **/** → Hub (visão geral + atalhos + atividades fake)
- **/comercial** → Comercial (pipeline/leads, metas — UI)
- **/financeiro** → Financeiro (recebíveis/pagamentos, relatórios — UI)
- **/central** → Central/Franchising (painéis por hierarquia — UI)

### Rotas futuras (placeholder)

- **/configuracoes** → Usuários/Permissões (UI)

---

## 8) Padrões visuais sugeridos

- Estilo: **corporativo simples**, limpo, com boa densidade de informação
- Desktop-first, mas sem quebrar no mobile
- Componentes consistentes para tabelas/listas (padrão “enterprise”)

> Regra prática: o usuário “bate o olho e entende”, e a UI não muda de comportamento de módulo pra módulo.

---

## 9) Checklist de review com stakeholders (Fase 1)

- A navegação faz sentido? (módulos, nomes, agrupamentos)
- O layout é confortável pra uso diário? (sidebar/header/scroll)
- A densidade de informação está ok? (nem vazio demais, nem poluído)
- Padrões de componentes estão consistentes? (botões, cards, tabelas, filtros)
- Quais páginas/indicadores são obrigatórios no Hub (mesmo fake) pra “vender” a visão?
