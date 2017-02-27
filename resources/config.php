<?php
/**
 * Auth component configuration file. Attention, configs might include runtime code which
 * depended on environment values only.
 *
 * @see AuthConfig
 */
use Spiral\Auth\Operators;

return [
    /*
      * Set of auth providers/operators responsible for user session support
      */
    'operators' => [
        /*
         * Uses active session storage to store user information
         */
        'session'    => [
            'class'   => Operators\SessionTokenOperator::class,
            'options' => [
                'section' => 'auth'
            ]
        ],

        /*
         * Utilized default HTTP basic auth protocol to authenticate user
         */
        'basic'      => [
            'class'   => Operators\BasicTokenOperator::class,
            'options' => []
        ],

        /*
         * Reads token hash from a specified header
         */
        'header'     => [
            'class'   => Operators\HeaderTokenOperator::class,
            'options' => [
                //Header to read token hash from
                'header'   => 'X-Auth-Token',

                //Token lifetime
                'lifetime' => 86400 * 14,

                //Persistent token storage
                'source'   => bind(\Spiral\Auth\Sources\TokenSourceInterface::class)
            ]
        ],

        /*
         * Stores authentication token into cookie
         */
        'cookie'     => [
            'class'   => Operators\CookieTokenOperator::class,
            'options' => [
                //Cookie name, do not forget to exclude cookie name from cookie manager
                'cookie'   => 'auth-token',

                //Cookie and token lifetime
                'lifetime' => 86400 * 7,

                //Persistent token storage
                'source'   => bind(\Spiral\Auth\Sources\TokenSourceInterface::class)
            ]
        ],

        /*
         * Stores authentication token into cookie as a remember-me cookie
         */
        'rememberMe' => [
            'enabled' => true,
            'class'   => Operators\CookieTokenOperator::class,
            'options' => [
                //Cookie name, do not forget to exclude cookie name from cookie manager
                'cookie'   => 'rememberMe-token',

                //Cookie and token lifetime
                'lifetime' => 86400 * 30,

                //Persistent token storage
                'source'   => bind(\Spiral\Auth\Sources\TokenSourceInterface::class)
            ]
        ],

        /*{{operators}}*/
    ]
];