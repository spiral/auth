<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Hashing;

/**
 * Works at top of default password hashes.
 */
class PasswordHasher
{
    /**
     * @param string $string
     * @return string
     */
    public function hash(string $string): string
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    /**
     * Compare user given password and stored hash safely.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function hashEquals(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Check if password needs to be rehashed.
     *
     * @param string $hash
     * @return bool
     */
    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }
}