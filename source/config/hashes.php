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
            /*
             * Below is an example of how you can enable additional password hashing
             * before password_hash function will be applied
             *
             * [
             *     'func'   => 'hash',
             *     'params' => ['sha256']
             * ],
             * [
             *     'func' => 'base64_encode',
             * ],
             *
             */
        ],
    ]
];