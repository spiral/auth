<?php
/**
 * Auth component configuration file. Attention, configs might include runtime code which
 * depended on environment values only.
 *
 * @see AuthConfig
 */
use Spiral\Auth\Providers;

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
            'class'   => Providers\SessionTokenOperator::class,
            'options' => [
                'key' => 'userID'
            ]
        ],
        /*{{providers}}*/
    ]
];