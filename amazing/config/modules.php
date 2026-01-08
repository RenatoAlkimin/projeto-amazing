<?php

return [
    'hub' => [
        'label' => 'Hub',
        'route' => 'hub.index',
        'permission' => null,
        'icon' => 'grid',
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

    'marketing' => [
        'label' => 'Marketing',
        'route' => 'marketing.index',
        'permission' => null, // ou 'marketing.view'
        'icon' => 'megaphone',
        'order' => 40,
        'section' => 'principal',
    ],

    'rh' => [
        'label' => 'RH',
        'route' => 'rh.index',
        'permission' => null, // ou 'rh.view'
        'icon' => 'users',
        'order' => 50,
        'section' => 'principal',
    ],

    'diagnostics' => [
         'label' => 'Diagnostics',
         'route' => 'diagnostics.index',
         'section' => 'Dev',
         'order' => 999,
         'icon' => 'wrench', // usa o que teu <x-icon> suportar
         'permission' => 'diagnostics.view', // futuro (fase 2)
    ],


];
