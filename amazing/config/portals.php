<?php

return [
    'amazing' => [
        'label' => 'Painel Amazing',
        'home_route' => 'amazing.home',
        'modules' => ['*'], // ✅ superadmin: acesso a todos os módulos do catálogo
    ],

    'vaapty' => [
        'label' => 'Painel Vaapty',
        'home_route' => 'vaapty.home',
        // 🔒 painel do produto: só módulos do produto (sem módulos internos de dev/controladoria)
        'modules' => ['hub', 'comercial', 'financeiro', 'marketing', 'rh'],
    ],
];
