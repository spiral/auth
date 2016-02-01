<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Auth;

/**
 * User which knows about it's password hash.
 */
interface PasswordAwareInterface extends UserInterface
{
    /**
     * @return string
     */
    public function getPasswordHash();
}