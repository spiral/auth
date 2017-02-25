<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 31.10.2016
 * Time: 18:58
 */

namespace Spiral\Auth\Configs;

use Spiral\Core\InjectableConfig;

/**
 * Defines how authorization tokens must be generated.
 */
class TokensConfig extends InjectableConfig
{
    const CONFIG = 'modules/auth/tokens';

    /**
     * @var array
     */
    protected $config = [
        'token' => [
            'selector' => [
                'length' => 12,
                'bin2hex' => true
            ],
            'hash' => [
                'length' => 64,
                'bin2hex' => true
            ],
        ]
    ];

    /**
     * @return string
     */
    public function tokenSelector():string
    {
        return $this->config['token']['selector'];
    }

    /**
     * @return string
     */
    public function tokenHash(): string
    {
        return $this->config['token']['hash'];
    }
}