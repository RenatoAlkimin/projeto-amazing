<?php

return [
    'default_portal' => env('AMAZING_DEFAULT_PORTAL', 'loja'),
    'default_scope'  => env('AMAZING_DEFAULT_SCOPE', 'default'),

    // Permite trocar portal via ?portal=... (somente em dev)
    'allow_portal_query_switch' => env('AMAZING_ALLOW_PORTAL_QUERY_SWITCH', false),
];
