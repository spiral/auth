<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 31.10.2016
 * Time: 12:43
 */

namespace Spiral\Auth\Hashes;


use Spiral\Auth\Configs\HashesConfig;

abstract class AbstractHashes
{
    /**
     * Name for config section. Will be used to fetch primary hashing operations.
     */
    const SECTION = null;

    /**
     * @var HashesConfig
     */
    protected $config;

    /**
     * AbstractHashes constructor.
     *
     * @param HashesConfig $config
     */
    public function __construct(HashesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Primary hashing.
     *
     * @param string $string
     * @return mixed
     */
    protected function primaryHashing($string)
    {
        foreach ($this->config->primaryHashing(static::SECTION) as $operation) {
            if (!empty($operation['params'])) {
                $params = $operation['params'];
                $params[] = $string;
            } else {
                $params = [$string];
            }

            $string = call_user_func_array($operation['func'], $params);
        }

        return $string;
    }

    /**
     * Make hash.
     *
     * @param string $string
     * @return string
     */
    abstract public function makeHash($string);

    /**
     * Compare hashes.
     *
     * @param string $string
     * @param string $hash
     * @return bool
     */
    abstract public function hashEquals($string, $hash);

    /**
     * If hash rehashing is needed.
     *
     * @param $hash
     * @return string
     */
    abstract public function rehashNeeded($hash);
}

if (!function_exists('hash_equals')) {
    function hash_equals($str1, $str2)
    {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }

            return !$ret;
        }
    }
}