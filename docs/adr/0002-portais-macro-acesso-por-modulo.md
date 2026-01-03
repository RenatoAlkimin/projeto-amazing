# ADR 0002 — Portal (Group) controla macro-acesso a módulos

## Status
Aceito

## Contexto
O sistema terá diferentes painéis (ex.: amazing/franchising/franqueado/loja) e precisamos controlar rapidamente **quais módulos cada portal enxerga**. Além de esconder o menu, é necessário bloquear acesso por URL direta.

## Decisão
Decidimos que o macro-acesso (portal → módulos) será definido por configuração e reforçado por middleware:

- Fonte de verdade: `config/portals.php` define `portal -> modules[]` (allowlist), com suporte a `'*'` (acesso total).
- Enforcement: middleware `module_enabled:<modulo>` impede acesso direto quando o portal não permite.

## Consequências
✅ Benefícios
- Governança simples: mudança de acesso acontece em um lugar.
- Consistência de segurança: não depende só do menu (URL direta também bloqueia).
- Base pronta para evoluir com permissões finas na Fase 2.

⚠️ Custos / Riscos
- Se a regra de “permitido” existir em mais de um lugar (sidebar vs middleware), pode haver divergência.
  - Recomendação: centralizar a regra em um serviço/helper único.
- Typos em `modules[]` podem passar despercebidos sem validação/teste.
