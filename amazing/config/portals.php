<?php

return [
    'amazing' => [
        'label' => 'Painel Amazing',
        'home_route' => 'amazing.home',
        'modules' => ['hub', 'comercial', 'financeiro', 'marketing', 'rh',  'diagnostics' ],
    ],

    'franchising' => [
        'label' => 'Painel Franchising',
        'home_route' => 'franchising.home',
        'modules' => ['hub', 'comercial', 'financeiro', 'marketing', 'rh'],
    ],

    'franqueado' => [
        'label' => 'Franqueado',
        'home_route' => 'franqueado.home',
        'modules' => ['hub', 'comercial', 'financeiro'],
    ],

    'franqueado_central' => [
        'label' => 'Central do Franqueado',
        'home_route' => 'franqueado_central.home',
        'modules' => ['hub', 'comercial', 'financeiro'],
    ],

    'loja' => [
        'label' => 'Loja',
        'home_route' => 'loja.home',
        'modules' => ['hub', 'comercial', 'financeiro'],
    ],
];
