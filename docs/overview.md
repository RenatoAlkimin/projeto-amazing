# Amazing (pré-projeto) — Hub Vaapty

## Visão geral
**Amazing** é o codename interno do hub da **Vaapty** (intermediação de vendas de veículos).  
A proposta é centralizar ferramentas internas por área (Comercial, Financeiro, Central/Franchising etc.) em uma interface única, consistente e escalável.

## Objetivo da fase atual (UI-only)
Nesta fase, o projeto é **apenas visual**:
- Layout base (sidebar + header)
- Navegação entre módulos
- Páginas placeholder e componentes reutilizáveis
- Mock de telas (tabelas, cards, listas, gráficos fake)

**Não faz parte agora:**
- Autenticação / permissões
- Banco de dados e CRUDs reais
- Integrações externas (bancos, notificações, storage real)
- Regras de negócio

## Público e contexto de uso (futuro)
- **Lojas físicas**: operação do dia-a-dia (cadastros, pipeline, documentos)
- **Central / Franchising**: visão de rede, hierarquia, padrões, auditoria
- **Financeiro**: extratos, conciliações, relatórios e processamento pesado

## Escopo futuro (direcionadores)
- 100+ lojas e **múltiplos níveis de hierarquia**
- Muitas contas/usuários com papéis diferentes
- Grande volume de imagens e anexos
- Extratos/arquivos bancários pesados (processamento assíncrono)
- Notificações web + mobile
- Performance e consistência de UX como prioridade

## Módulos iniciais (UI)
- **Hub**: entrada e navegação
- **Comercial**: pipeline, leads, propostas (placeholder)
- **Financeiro**: recebíveis, extratos, conciliações (placeholder)
- **Central**: franquias/hierarquia, visão macro (placeholder)

## Princípios de produto (pra não virar bagunça)
- **Consistência visual**: mesmas regras de tabela, formulário, botões, espaçamentos
- **Modularidade**: cada módulo cresce sem quebrar os demais
- **Escalabilidade**: estrutura pronta para multi-loja, permissões e auditoria
- **Performance**: listas paginadas, evitar telas “pesadas”, cache/filas quando entrar backend
