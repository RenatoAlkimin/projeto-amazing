# ADR 0006 — Módulo **Diagnostics** (dev-only) para verificação de integridade por portal

## Status
Aceito

## Contexto
O projeto está na **Fase 1 (UI-only)**: sem DB, sem regra de negócio e sem integrações — mas já com **governança por config** (portais → módulos), rotas escopadas (`/s/{scope}`) e UI (sidebar/topbar) derivada do catálogo de módulos.

Na prática, durante o desenvolvimento e principalmente ao rodar a suíte de testes, surgem problemas comuns que queremos detectar rápido e de forma “visível”, por exemplo:
- divergências entre `config/modules.php` e as rotas realmente registradas;
- módulos configurados sem arquivo de rotas;
- comportamento de acesso por portal (200/403) divergindo da sidebar;
- problemas de build de front-end (ex.: `public/build/manifest.json` ausente) e presença de `public/hot`.

**Restrições**
- O módulo não pode virar dependência de produção nem “vazar” para portais finais.
- Precisa respeitar a governança existente (portal → módulos + middleware).
- Deve ser **barato** (sem DB) e **seguro por padrão** (apagado quando desabilitado).

## Decisão
Criar um novo módulo chamado **`diagnostics`**, visível e acessível **somente** no portal **`amazing`** (portal dev), com *feature flag*.

### 1) Catálogo e governança
- O módulo `diagnostics` entra no **catálogo** em `config/modules.php` (com `route => diagnostics.index`).
- O portal `amazing` inclui `diagnostics` na sua allowlist em `config/portals.php`.
- Outros portais **não** incluem `diagnostics`.

### 2) Rotas
- Rota escopada: `GET /s/{scope}/diagnostics`
- Nome: `diagnostics.index`
- Arquivo: `routes/modules/diagnostics.php`
- Continua sob o grupo escopado (`routes/groups/scoped_modules.php`) e sob o middleware:
  - `module_enabled:diagnostics` (enforcement 403 quando o portal não permite)

### 3) Feature flag (dev-only)
Além do enforcement por portal, o módulo tem uma flag:
- `config('amazing.enable_diagnostics', false)`

Comportamento:
- **Desabilitado (padrão):** retorna **404** (endpoint “some do mapa”).
- **Habilitado:** a tela renderiza e exibe informações de diagnóstico.

Implementação:
- Guard no controller (ex.: no `index`), abortando com 404 quando a flag estiver `false`.

### 4) Conteúdo da página
A tela de Diagnostics mostra, no mínimo:
- scope atual;
- portal atual;
- status do Vite:
  - existência de `public/build/manifest.json`
  - existência de `public/hot`
- módulos do catálogo e se a rota configurada existe;
- matriz “portal → módulos permitidos” (resolvendo `'*'` quando aplicável).

### 5) Testes automatizados (contrato)
Foi adicionado `Tests\Feature\PortalModuleAccessTest` para fixar o contrato:

- **Acesso por portal (200/403):**
  - módulos permitidos pelo portal → **200**
  - módulos não permitidos → **403**
- **Diagnostics desabilitado:**
  - `diagnostics.index` retorna **404** quando `enable_diagnostics=false`
- **Sidebar:**
  - “Diagnostics” aparece **apenas** quando `portal=amazing` (e flag habilitada)

### Exemplos
- Home do Hub (com troca de portal em dev, se permitido):
  - `/s/default?portal=amazing`
- Página de diagnóstico:
  - `/s/default/diagnostics?portal=amazing`

## Consequências
✅ Benefícios
- **Debug rápido e profissional**: um “painel de saúde” do protótipo, sem depender de olhar logs ou sair caçando configs.
- **Reduz regressões**: fixa o contrato de acesso por portal + presença de rotas/arquivos com testes.
- **Onboarding melhor**: facilita entender “o que está habilitado” e “por quê”.

⚠️ Custos / Riscos / Trade-offs
- **Risco de vazamento de informação** se habilitado em produção (por isso: flag `false` por padrão + 404).
- **Manutenção contínua**: a tela deve acompanhar a evolução do catálogo (campos novos, novas validações).
- **Falso senso de segurança**: Diagnostics ajuda, mas não substitui testes e pipeline (CI deve continuar rodando build/test conforme padrão).

## Referências
- `docs/arquitetura.md` (seção de governança por config + testes)
- `config/modules.php`
- `config/portals.php`
- `config/amazing.php` (feature flags)
- `routes/groups/scoped_modules.php`
- `routes/modules/diagnostics.php`
- `app/Http/Controllers/Diagnostics/DiagnosticsController.php`
- `tests/Feature/PortalModuleAccessTest.php`
