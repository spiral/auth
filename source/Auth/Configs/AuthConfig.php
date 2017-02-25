<?php
/**
 * Spiral Framework.
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 */

namespace Spiral\Auth\Configs;

use Spiral\Core\InjectableConfig;

/**
 * Set of auth operators used to handle user tokens.
 */
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
        'operators' => []
    ];

    /**
     * All token operator names.
     *
     * @return array
     */
    public function getOperators(): array
    {
        return array_keys($this->config['operators']);
    }

    /**
     * @param string $name
     * @return string
     */
    public function operatorClass(string $name): string
    {
        return $this->config['operators'][$name]['class'];
    }

    /**
     * @param string $name
     * @return array
     */
    public function operatorOptions(string $name): array
    {
        return $this->config['operators'][$name]['options'];
    }
}