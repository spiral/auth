<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 31.10.2016
 * Time: 18:58
 */

namespace Spiral\Auth\Configs;


use Spiral\Core\InjectableConfig;

class GeneratorConfig extends InjectableConfig
{
    /**
     * Configuration section.
     */
    const CONFIG = 'modules/auth/generator';

    /**
     * @var array
     */
    protected $config = [
        'token' => [
            'selector' => [
                'length'  => 12,
                'bin2hex' => true
            ],
            'hash'     => [
                'length'  => 64,
                'bin2hex' => true
            ],
        ]
    ];

    /**
     * @return string
     */
    public function tokenSelector()
    {
        return $this->config['token']['selector'];
    }

    /**
     * @return string
     */
    public function tokenHash()
    {
        return $this->config['token']['hash'];
    }
}