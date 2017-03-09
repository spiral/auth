<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @package   spiralFramework
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2011
 */

namespace Spiral\Auth\Hashing;

class StringHasher
{
    /**
     * @var string
     */
    private $algo;

    /**
     * @param string $algo
     */
    public function __construct(string $algo = 'sha256')
    {
        $this->algo = $algo;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function hash(string $string): string
    {
        return hash($this->algo, $string);
    }

    /**
     * @param string $string
     * @param string $hash
     *
     * @return bool
     */
    public function hashEquals(string $string, string $hash): bool
    {
        return hash_equals($string, $hash);
    }
}