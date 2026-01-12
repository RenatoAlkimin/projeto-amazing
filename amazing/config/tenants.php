<?php

return [
    // Plano default: use como “fallback” pra scopes não cadastrados explicitamente
    'default' => [
        'label' => 'Default',
        // Dica: deixe o default bem “mínimo” pra não vazar acesso por engano
        'modules' => ['*'],
    ],

    // exemplos
     'loja_001' => [
         'label' => 'Loja 001',
         'modules' => ['hub', 'comercial', 'rh' ],
     ],
    
     'loja_002' => [
         'label' => 'Loja 002',
         'modules' => ['rh'], // tudo (mas sempre vai intersectar com o portal)
     ],
];
