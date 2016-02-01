<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J), Lev Seleznev
 */
namespace Spiral\Auth\Sources;

use Spiral\Auth\UserSourceInterface;
use Spiral\Auth\PasswordAwareInterface;

interface UsernameUserSourceInterface extends UserSourceInterface
{
    /**
     * @param string $username
     * @return PasswordAwareInterface|null
     */
    public function findByUsername($username);
}