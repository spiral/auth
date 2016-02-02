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
     * Set of auth providers responsible for session support (
     */
    'operators'  => [
        'session' => [
            'class'   => Operators\SessionTokenOperator::class,
            'options' => [
                'key' => 'userID'
            ]
        ],
        'basic' => [
            'class'   => Operators\HTTPBasicSessionOperator::class,
            'options' => [
                'key' => 'userID' //used for session
            ]
        ],
        'cookie' => [
            'class'   => Operators\CookieTokenOperator::class,
            'options' => [
                'name' => 'auth-token',
                'lifetime' => 0,
                'source' => \Database\Sources\TokenSource::class,
            ]
        ],
        'rememberMe' => [
            'class'   => Operators\CookieTokenOperator::class,
            'options' => [
                'name' => 'remember-me',
                'lifetime' => 86400*7, //1 week
                'storage' => [
                    'source' => \Database\Sources\TokenSource::class,
                ],
            ]
        ],
        /*{{providers}}*/
    ]
];