<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */

namespace Spiral\Auth\Sources;

use Spiral\Auth\PasswordAwareInterface;

interface UsernameSourceInterface extends UserSourceInterface
{
    /**
     * @param string $username
     *
     * @return PasswordAwareInterface|null
     */
    public function findByUsername(string $username);
}