<?php

return [
    'hub' => [
        'label' => 'Hub',
        'route' => 'hub.index',
        'permission' => null,
        'icon' => 'grid',     // opcional
        'order' => 10,
        'section' => 'principal',
    ],

    'comercial' => [
        'label' => 'Comercial',
        'route' => 'comercial.index',
        'permission' => 'comercial.view',
        'icon' => 'briefcase',
        'order' => 20,
        'section' => 'principal',
    ],

    'financeiro' => [
        'label' => 'Financeiro',
        'route' => 'financeiro.index',
        'permission' => 'financeiro.view',
        'icon' => 'wallet',
        'order' => 30,
        'section' => 'principal',
    ],

    'central' => [
        'label' => 'Central',
        'route' => 'central.index',
        'permission' => 'central.view',
        'icon' => 'settings',
        'order' => 40,
        'section' => 'admin',
    ],
];
