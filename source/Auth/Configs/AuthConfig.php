<?php
/**
 * Spiral Framework.
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth\Configs;

use Spiral\Core\InjectableConfig;

class AuthConfig extends InjectableConfig
{
    /**
     * Configuration section.
     */
    const CONFIG = 'modules/auth';

    /**
     * @var array
     */
    protected $config = [
        'userSource' => '',
        'providers'  => [

        ]
    ];

    /**
     * @return string
     */
    public function userSource()
    {
        return $this->config['userSource'];
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return array_keys($this->config['providers']);
    }

    /**
     * @param string $name
     * @return string
     */
    public function providerClass($name)
    {
        return $this->config['providers'][$name]['class'];
    }

    /**
     * @param string $name
     * @return array
     */
    public function providerOptions($name)
    {
        return $this->config['providers'][$name]['options'];
    }
}