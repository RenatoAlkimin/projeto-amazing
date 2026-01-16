<?php

return [
    /**
     * Portal padrão quando a sessão ainda não tem portal definido
     * (ex.: acesso direto em /s/{scope}/..., sessão nova, etc).
     *
     * ✅ Importante: manter como 'loja' pra garantir que 'amazing' só entra via rota /amazing.
     */
    'default_portal' => env('AMAZING_DEFAULT_PORTAL', 'loja'),

    /**
     * Scope padrão caso não venha na rota.
     */
    'default_scope' => env('AMAZING_DEFAULT_SCOPE', 'default'),

    /**
     * Permite trocar portal via ?portal=... SOMENTE em dev/test.
     * O middleware ResolvePortal já restringe por environment.
     */
    'allow_portal_query_switch' => env('AMAZING_ALLOW_PORTAL_QUERY_SWITCH', false),

    /**
     * Feature flag para Diagnostics (ideal pra dev/staging).
     * Em produção: manter false.
     */
    'enable_diagnostics' => env('AMAZING_ENABLE_DIAGNOSTICS', false),
];
