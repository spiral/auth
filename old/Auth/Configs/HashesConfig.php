<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 31.10.2016
 * Time: 12:19
 */

namespace Spiral\Auth\Configs;

use Spiral\Core\InjectableConfig;

class HashesConfig extends InjectableConfig
{
    /**
     * Configuration section.
     */
    const CONFIG = 'modules/auth/hashes';

    /**
     * @var array
     */
    protected $config = [
        'token'    => [],
        'password' => []
    ];

    /**
     * @return string
     */
    public function passwordHashAlgo()
    {
        return $this->config['password']['hash']['algo'];
    }

    /**
     * @param string $section
     * @return array
     */
    public function primaryHashing($section)
    {
        return (array)$this->config[$section]['primaryHashing'];
    }
}