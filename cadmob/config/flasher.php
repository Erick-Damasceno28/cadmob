<?php

return [
    'default' => 'noty',

    'root_script' => true,

    'plugins' => [
        'noty' => [
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/noty@3.1.4/lib/noty.min.js',
            ],
            'styles' => [
                'https://cdn.jsdelivr.net/npm/noty@3.1.4/lib/noty.min.css',
                'https://cdn.jsdelivr.net/npm/noty@3.1.4/lib/themes/mint.css',
            ],
            'options' => [
                'theme' => 'mint',
                'timeout' => 3000,
                'layout' => 'topRight',
            ],
        ],
    ],
];
