<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 28.10.2016
 * Time: 12:23
 */

namespace Spiral\Auth\Hashes;

class TokenHashes extends AbstractHashes
{
    /**
     * {@inheritdoc}
     */
    const SECTION = 'token';

    /**
     * {@inheritdoc}
     */
    public function makeHash($string)
    {
        $string = $this->primaryHashing($string);

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    public function hashEquals($string, $hash)
    {
        return hash_equals($this->makeHash($string), $hash);
    }

    /**
     * {@inheritdoc}
     */
    public function rehashNeeded($hash)
    {
        return false;
    }
}