<?php
namespace Spiral\Auth;

use Spiral\Auth\Configs\GeneratorConfig;
use Spiral\Support\Strings;

/**
 * Abstraction for pseudo-random generator.
 */
class RandomGenerator
{
    /**
     * @var GeneratorConfig
     */
    private $config;

    /**
     * RandomGenerator constructor.
     *
     * @param GeneratorConfig $config
     */
    public function __construct(GeneratorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param int  $length
     * @param bool $bin2hex
     * @return string
     */
    public function generate($length, $bin2hex = false)
    {
        $hash = Strings::random($length);
        if (!empty($bin2hex)) {
            return bin2hex($hash);
        }

        return $hash;
    }

    /**
     * @return string
     */
    public function generateTokenSelector()
    {
        $params = $this->config->tokenSelector();

        return $this->generate($params['length'], $params['bin2hex']);
    }

    /**
     * @return string
     */
    public function generateTokenHash()
    {
        $params = $this->config->tokenHash();

        return $this->generate($params['length'], $params['bin2hex']);
    }
}