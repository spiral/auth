<?php
return [
    'token'    => [
        'primaryHashing' => [
            [
                'func'   => 'hash',
                'params' => ['sha256']
            ],
        ],
    ],
    'password' => [
        'hash'           => [
            'algo' => PASSWORD_DEFAULT
        ],
        'primaryHashing' => [
            [
                'func'   => 'hash',
                'params' => ['sha256']
            ],
            [
                'func' => 'base64_encode',
            ],
        ],
    ]
];