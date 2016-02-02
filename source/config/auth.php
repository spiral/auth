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
     * Must point to the class name used to resolve users via username or id by default.
     */
    'userSource' => null,

    /*
     * Set of auth providers responsible for user session support
     */
    'operators'  => [
        /*
         * Uses active session storage to store user information
         */
        'session' => [
            'class'   => Operators\SessionTokenOperator::class,
            'options' => [
                'key' => 'userID'
            ]
        ],
        /*
         * Utilized default HTTP basic auth protocol to authenticate user
         */
        'basic'   => [
            'class' => Operators\BasicTokenOperator::class,
        ],

        /*
         * Reads token hash from a specified header
         */
        'header'  => [
            'class'   => Operators\CookieTokenOperator::class,
            'options' => [
                //Header to read token hash from
                'header'      => 'X-Auth-Token',

                //Persistent token storage
                'sourceClass' => \Spiral\Auth\Sources\TokenSourceInterface::class,
            ]
        ],

        /*
         * Stores authentication token into cookie
         */
        'cookie'  => [
            'class'   => Operators\CookieTokenOperator::class,
            'options' => [
                //Cookie name, do not forget to exclude cookie name from cookie manager
                'cookie'      => 'auth-token',

                //Cookie and token lifetime
                'lifetime'    => 86400 * 7,

                //Persistent token storage
                'sourceClass' => \Spiral\Auth\Sources\TokenSourceInterface::class,
            ]
        ],

        /*{{providers}}*/
    ]
];