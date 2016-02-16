<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 16.02.2016
 * Time: 20:44
 */

namespace Spiral\Auth;

class Hasher
{
    /**
     * Password hashing.
     *
     * @param string     $string
     * @param array|null $options
     * @return bool|string
     */
    public function passwordHash($string, $options = null)
    {
        return password_hash($string, PASSWORD_DEFAULT, $options);
    }

    /**
     * Compare hashes.
     *
     * @param string $string
     * @param string $hash
     * @return bool
     */
    public function verifyHashes($string, $hash)
    {
        return password_verify($string, $hash);
    }

    /**
     * If hash rehashing is needed.
     *
     * @param string     $hash
     * @param array|null $options
     * @return string
     */
    public function needsRehash($hash, $options = null)
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT, $options);
    }
}