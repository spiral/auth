<?php
/**
 * Auth component configuration file. Attention, configs might include runtime code which
 * depended on environment values only.
 *
 * @see AuthConfig
 */
use Spiral\Auth\Operators;

return [
    //Default token provider
    'defaultOperator' => 'cookie',

    /*
      * Set of auth providers/operators responsible for user session support
      */
    'operators'       => [
        /*
         * Uses active session storage to store user information
         */
        'session'     => [
            'class'   => Operators\SessionOperator::class,
            'options' => [
                'section' => 'auth'
            ]
        ],

        /*
         * Utilized default HTTP basic auth protocol to authenticate user
         */
        'basic'       => [
            'class'   => Operators\HttpOperator::class,
            'options' => []
        ],

        /*
         * Reads token hash from a specified header
         */
        'header'      => [
            'class'   => Operators\PersistentOperator::class,
            'options' => [
                //Token lifetime
                'lifetime' => 86400 * 14,

                //Persistent token storage
                'source'   => bind(\Spiral\Auth\Database\Sources\AuthTokenSource::class),

                //How to read and write tokens in request
                'bridge'   => bind(Operators\Bridges\HeaderBridge::class, [
                    'header' => 'X-Auth-Token',
                ])
            ]
        ],

        /*
         * Stores authentication token into cookie
         */
        'cookie'      => [
            'class'   => Operators\PersistentOperator::class,
            'options' => [
                //Cookie and token lifetime
                'lifetime' => 86400 * 7,

                //Persistent token storage
                'source'   => bind(\Spiral\Auth\Database\Sources\AuthTokenSource::class),

                //How to read and write tokens in request
                'bridge'   => bind(Operators\Bridges\CookieBridge::class, [
                    'cookie' => 'auth-token',
                ])
            ]
        ],

        /*
         * Stores authentication token into cookie as a remember-me cookie
         */
        'long' => [
            'class'   => Operators\PersistentOperator::class,
            'options' => [
                //Cookie and token lifetime
                'lifetime' => 86400 * 30,

                //Persistent token storage
                'source'   => bind(\Spiral\Auth\Database\Sources\AuthTokenSource::class),

                //How to read and write tokens in request
                'bridge'   => bind(Operators\Bridges\CookieBridge::class, [
                    'cookie' => 'long-token',
                ])
            ]
        ],

        /*{{operators}}*/
    ]
];