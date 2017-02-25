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
        'operators'  => [

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
    public function getOperators()
    {
        return array_keys($this->config['operators']);
    }

    /**
     * @param string $name
     * @return string
     */
    public function operatorClass($name)
    {
        return $this->config['operators'][$name]['class'];
    }

    /**
     * @param string $name
     * @return array
     */
    public function operatorOptions($name)
    {
        return $this->config['operators'][$name]['options'];
    }
}