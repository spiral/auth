<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 28.10.2016
 * Time: 12:23
 */

namespace Spiral\Auth\Hashes;

class PasswordHashes extends AbstractHashes
{
    /**
     * {@inheritdoc}
     */
    const SECTION = 'password';

    /**
     * {@inheritdoc}
     */
    public function makeHash($string)
    {
        $string = $this->primaryHashing($string);

        return password_hash($string, $this->config->passwordHashAlgo());
    }

    /**
     * {@inheritdoc}
     */
    public function hashEquals($string, $hash)
    {
        return password_verify($this->primaryHashing($string), $hash);
    }

    /**
     * {@inheritdoc}
     */
    public function rehashNeeded($hash)
    {
        return password_needs_rehash($hash, $this->config->passwordHashAlgo());
    }
}